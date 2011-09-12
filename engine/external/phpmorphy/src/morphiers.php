<?php
 /**
 * This file is part of phpMorphy library
 *
 * Copyright c 2007-2008 Kamaev Vladimir <heromantor@users.sourceforge.net>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place - Suite 330,
 * Boston, MA 02111-1307, USA.
 */

require_once(PHPMORPHY_DIR . '/gramtab.php');
require_once(PHPMORPHY_DIR . '/unicode.php');
 
// ----------------------------
// Morphier interface
// ----------------------------
interface phpMorphy_Morphier_Interface {
	function getBaseForm($word);
	function getAllForms($word);
	function getPseudoRoot($word);
	function getPartOfSpeech($word);
	function getWordDescriptor($word);
}

class phpMorphy_Morphier_Empty implements phpMorphy_Morphier_Interface {
	function getBaseForm($word) { return false; }
	function getAllForms($word) { return false; }
	function getAllFormsWithGramInfo($word) { return false; }
	function getPseudoRoot($word) { return false; }
	function getPartOfSpeech($word) { return false; }
	function getWordDescriptor($word) { return false; }
}

// ----------------------------
// Annot decoder
// ----------------------------
interface phpMorphy_AnnotDecoder_Interface {
	function decode($annotsRaw, $withBase);
};

abstract class phpMorphy_AnnotDecoder_Base implements phpMorphy_AnnotDecoder_Interface {
	const INVALID_ANCODE_ID = 0xFFFF;
	protected
		$ends,
		$unpack_str,
		$block_size;
		
	function __construct($ends) {
		$this->ends = $ends;
		
		$this->unpack_str = $this->getUnpackString();
		$this->block_size = $this->getUnpackBlockSize();
	}
	
	abstract protected function getUnpackString();
	abstract protected function getUnpackBlockSize();

	function decode($annotRaw, $withBase) {
		$unpack_str = $this->unpack_str;
		$unpack_size = $this->block_size;
		
		$result = unpack("Vcount/$unpack_str", $annotRaw);
		
		if(false === $result) {
			throw new phpMorphy_Exception("Invalid annot string '$annotRaw'");
		}
		
		if($result['common_ancode'] == self::INVALID_ANCODE_ID) {
			$result['common_ancode'] = null;
		}
		
		$count = $result['count'];
		
		$result = array($result);
		
		if($count > 1) {
			for($i = 0; $i < $count - 1; $i++) {
				$res = unpack($unpack_str, substr($annotRaw, 4 + ($i + 1) * $unpack_size, $unpack_size));
				
				if($res['common_ancode'] == self::INVALID_ANCODE_ID) {
					$res['common_ancode'] = null;
				}
				
				$result[] = $res;
			}
		}
		
		if($withBase) {
			$items = explode($this->ends, substr($annotRaw, 4 + $count * $unpack_size));
			for($i = 0; $i < $count; $i++) {
				$result[$i]['base_prefix'] = $items[$i * 2];
				$result[$i]['base_suffix'] = $items[$i * 2 + 1];
			}
		}
		
		return $result;
	}
}

class phpMorphy_AnnotDecoder_Common extends phpMorphy_AnnotDecoder_Base {
	protected function getUnpackString() {
		return 'Voffset/vcplen/vplen/vflen/vcommon_ancode/vforms_count/vpacked_forms_count/vaffixes_size/vform_no/vpos_id';
//		return 'Voffset/vcplen/vplen/vflen/vcommon_ancode/vforms_count/vpacked_forms_count/vaffixes_size/vpos_id';
	}
	
	protected function getUnpackBlockSize() {
		return 22;
	}
}

class phpMorphy_AnnotDecoder_Predict extends phpMorphy_AnnotDecoder_Common {
	protected function getUnpackString() {
//		return 'Voffset/vcplen/vplen/vflen/vcommon_ancode/vforms_count/vpacked_forms_count/vaffixes_size/vform_no/vpos_id/vfreq';
		return parent::getUnpackString() . '/vfreq';
	}
	
	protected function getUnpackBlockSize() {
		return parent::getUnpackBlockSize() + 2;
	}
}

class phpMorphy_AnnotDecoder_Factory {
    protected static $instances = array();
    
    protected
        $cache_common,
        $cache_predict,
        $eos;
    
    protected function __construct($eos) {
        $this->eos = $eos;
    }
    
    static function create($eos) {
        if(!isset(self::$instances[$eos])) {
            self::$instances[$eos] = new phpMorphy_AnnotDecoder_Factory($eos);
        }
        
        return self::$instances[$eos];
    }
    
    function getCommonDecoder() {
        if(!isset($this->cache_common)) {
            $this->cache_common = $this->instantinate('common');
        }
        
        return $this->cache_common;
    }
    
    function getPredictDecoder() {
        if(!isset($this->cache_predict)) {
            $this->cache_predict = $this->instantinate('predict');
        }
        
        return $this->cache_predict;
    }
    
    protected function instantinate($type) {
        $clazz = 'phpMorphy_AnnotDecoder_' . ucfirst(strtolower($type));
        
        return new $clazz($this->eos);
    }
}

// ----------------------------
// Helper
// ----------------------------
class phpMorphy_Morphier_Helper {
	protected
		$graminfo,
		$annot_decoder,
		$char_size,
		$ends,
		$gramtab,
		$resolve_pos;
	
	function __construct(phpMorphy_GramInfo_Interace $graminfo, phpMorphy_GramTab_Interface $gramtab, $resolvePartOfSpeech) {
		$this->graminfo = $graminfo;
		$this->gramtab = $gramtab;
		$this->resolve_pos = (bool)$resolvePartOfSpeech;
		
		$this->char_size = $graminfo->getCharSize();

		$this->ends = $graminfo->getEnds();
	}
	
	function setAnnotDecoder(phpMorphy_AnnotDecoder_Interface $annotDecoder) {
		$this->annot_decoder = $annotDecoder;
	}
	
	function getGramInfo() {
		return $this->graminfo;
	}
	
	function getGramTab() {
		return $this->gramtab;
	}
	
	function isResolvePartOfSpeech() {
		return $this->resolve_pos;
	}	
	
	function getEndOfString() {
		return $this->ends;
	}
	
	function resolvePartOfSpeech($posId) {
		return $this->gramtab->resolvePartOfSpeechId($posId);
	}
	
	function getGrammems($ancodeId) {
		return $this->gramtab->getGrammems($ancodeId);
	}
	
	function getGrammemsAndPartOfSpeech($ancodeId) {
		return array(
			$this->gramtab->getPartOfSpeech($ancodeId),
			$this->gramtab->getGrammems($ancodeId)
		);
	}
	
	protected function getBaseAndPrefix($word, $cplen, $plen, $flen) {
		if($flen) {
			$base = substr($word, $cplen + $plen, -$flen);
		} else {
			if($cplen || $plen) {
				$base = substr($word, $cplen + $plen);
			} else {
				$base = $word;
			}
		}
		
		$prefix = $cplen ? substr($word, 0, $cplen) : '';
		
		return array($base, $prefix);
	}
	
	function extractPartOfSpeech($annot) {
		if($this->resolve_pos) {
			return $this->resolvePartOfSpeech($annot['pos_id']);
		} else {
			return $annot['pos_id'];
		}
	}
	
	function getPartOfSpeech($word, $annots) {
		if(false === $annots) {
			return false;
		}
		
		$result = array();
		
		foreach($this->decodeAnnot($annots, false) as $annot) {
			$result[$this->extractPartOfSpeech($annot)] = 1;
		}
		
		return array_keys($result);
	}
	
	function getBaseForm($word, $annots) {
		if(false === $annots) {
			return false;
		}
		
		$annots = $this->decodeAnnot($annots, true);

		return $this->composeBaseForms($word, $annots);
	}
	
	function getPseudoRoot($word, $annots) {
		if(false === $annots) {
			return false;
		}
		
		$annots = $this->decodeAnnot($annots, false);
		
		$result = array();
		
		foreach($annots as $annot) {
			list($base) = $this->getBaseAndPrefix(
				$word,
				$annot['cplen'],
				$annot['plen'],
				$annot['flen']
			);
			
			$result[$base] = 1;
		}
		
		return array_keys($result);
	}
		
	function getAllForms($word, $annots) {
		if(false === $annots) {
			return false;
		}
		
		$annots = $this->decodeAnnot($annots, false);
		
		return $this->composeForms($word, $annots);
	}
	
	function getAllFormsWithAncodes($word, $annots, &$foundFormNo = array()) {
		if(false === $annots) {
			return false;
		}
		
		$annots = $this->decodeAnnot($annots, false);
		
		return $this->composeFormsWithAncodes($word, $annots, $foundFormNo);
	}
	
	function getAllAncodes($word, $annots) {
		if(false === $annots) {
			return false;
		}
		
		$result = array();
		
		foreach($annots as $annot) {
			$result[] = $this->graminfo->readAncodes($annot);
		}
		
		return $result;
	}
	
	protected function composeBaseForms($word, $annots) {
		$result = array();
		
		foreach($annots as $annot) {
			
			if($annot['form_no'] > 0) {
				list($base, $prefix) = $this->getBaseAndPrefix(
					$word,
					$annot['cplen'],
					$annot['plen'],
					$annot['flen']
				);
				
				$result[$prefix . $annot['base_prefix'] . $base . $annot['base_suffix']] = 1;
			} else {
				$result[$word] = 1;
			}
		}
		
		return array_keys($result);
	}
	
	protected function composeForms($word, $annots) {
		$result = array();
		
		foreach($annots as $annot) {
			list($base, $prefix) = $this->getBaseAndPrefix(
				$word,
				$annot['cplen'],
				$annot['plen'],
				$annot['flen']
			);
			
			// read flexia
			$flexias = $this->graminfo->readFlexiaData($annot);
			
			for($i = 0, $c = count($flexias); $i < $c; $i += 2) {
				$result[$prefix . $flexias[$i] . $base . $flexias[$i + 1]] = 1;
			}
		}
		
		return array_keys($result);
	}
	
	protected function composeFormsWithAncodes($word, $annots, &$foundFormNo) {
		$result = array();
		
		foreach($annots as $annotIdx => $annot) {
			list($base, $prefix) = $this->getBaseAndPrefix(
				$word,
				$annot['cplen'],
				$annot['plen'],
				$annot['flen']
			);
			
			// read flexia
			$flexias = $this->graminfo->readFlexiaData($annot);
			$ancodes = $this->graminfo->readAncodes($annot);
			
            $found_form_no = $annot['form_no'];
            
            $foundFormNo = !is_array($foundFormNo) ? array() : $foundFormNo;
            
			for($i = 0, $c = count($flexias); $i < $c; $i += 2) {
                $form_no = $i / 2;
				$word = $prefix . $flexias[$i] . $base . $flexias[$i + 1];
				
                if($found_form_no == $form_no) {
                	$count = count($result);
                    $foundFormNo[$annotIdx]['low'] = $count;
                    $foundFormNo[$annotIdx]['high'] = $count + count($ancodes[$form_no]) - 1;
                }
                
				foreach($ancodes[$form_no] as $ancode) {
					$result[] = array($word, $ancode);
				}
			}
		}
		
		return $result;
	}
	
	function decodeAnnot($annotsRaw, $withBase) {
		if(is_array($annotsRaw)) {
			return $annotsRaw;
		} else {
			return $this->annot_decoder->decode($annotsRaw, $withBase);
		}
	}
}

// ----------------------------
// WordDescriptor
// ----------------------------
// TODO: extend ArrayObject?
class phpMorphy_WordDescriptor_Collection implements Countable, IteratorAggregate, ArrayAccess {
	protected
		$word,
		$descriptors = array(),
		$helper;
	
	function __construct($word, $annots, phpMorphy_Morphier_Helper $helper) {
		$this->word = (string)$word;
		$this->annots = false === $annots ? false : $helper->decodeAnnot($annots, true);
		
		$this->helper = $helper;
		
		if(false !== $this->annots) {
			foreach($this->annots as $annot) {
				$this->descriptors[] = $this->createDescriptor($word, $annot, $helper);
			}
		}
	}
	
	protected function createDescriptor($word, $annot, phpMorphy_Morphier_Helper $helper) {
		return new phpMorphy_WordDescriptor($word, $annot, $helper);
	}
	
	function getDescriptor($index) {
		if(!$this->offsetExists($index)) {
			throw new phpMorphy_Exception("Invalid index '$index' specified");
		}
		
		return $this->descriptors[$index];
	}
    
    function getByPartOfSpeech($poses) {
        $result = array();
        settype($poses, 'array');
        
        foreach($this as $desc) {
            if($desc->hasPartOfSpeech($poses)) {
                $result[] = $desc;
            }
        }
        
//        return count($result) ? $result : false;
        return $result;
    }
	
	function offsetExists($off) {
		return isset($this->descriptors[$off]);
	}
	
	function offsetUnset($off) {
		throw new phpMorphy_Exception(__CLASS__ . " is not mutable");
	}
	
	function offsetSet($off, $value) {
		throw new phpMorphy_Exception(__CLASS__ . " is not mutable");
	}
	
	function offsetGet($off) {
		return $this->getDescriptor($off);
	}
	
	function count() {
		return count($this->descriptors);
	}
	
	function getIterator() {
		return new ArrayIterator($this->descriptors);
	}
}

class phpMorphy_WordForm {
	protected
		$word,
		$form_no,
		$pos_id,
		$grammems
		;
	
	function __construct($word, $form_no, $pos_id, $grammems) {
		$this->word = (string)$word;
		$this->form_no = (int)$form_no;
		$this->pos_id = $pos_id;
		
		sort($grammems);
		$this->grammems = $grammems;
	}
	
	function getPartOfSpeech() {
		return $this->pos_id;
	}
	
	function getGrammems() {
		return $this->grammems;
	}
	
	function hasGrammems($grammems) {
        $grammes_count = count($grammems);
		return $grammes_count && count(array_intersect($grammems, $this->grammems)) == $grammes_count;
	}
	
	function getWord() {
		return $this->word;
	}
	
	function getFormNo() {
		return $this->form_no;
	}
}

class phpMorphy_WordDescriptor implements Countable, ArrayAccess, IteratorAggregate {
	protected
		$word,
		$annot,
		$helper,
		$cached_forms,
		$cached_base,
		$cached_pseudo_root,
		$all_forms,
        $found_form_no,
		$common_ancode_grammems;
	
	function __construct($word, $annot, phpMorphy_Morphier_Helper $helper) {
		$this->word = (string)$word;
		$this->annot = array($annot);
		
		$this->helper = $helper;
	}
	
	function getPseudoRoot() {
		if(!isset($this->cached_pseudo_root)) {
			list($this->cached_pseudo_root) = $this->helper->getPseudoRoot($this->word, $this->annot);
		}
		
		return $this->cached_pseudo_root;
	}
	
	function getBaseForm() {
		if(!isset($this->cached_base)) {
			list($this->cached_base) = $this->helper->getBaseForm($this->word, $this->annot);
		}
		
		return $this->cached_base;
	}
	
	function getAllForms() {
		if(!isset($this->cached_forms)) {
			$this->cached_forms = $this->helper->getAllForms($this->word, $this->annot);
		}
		
		return $this->cached_forms;
	}
	
	function getWordForm($index) {
		$this->readAllForms();
		
		if(!$this->offsetExists($index)) {
			throw new phpMorphy_Exception("Invalid index '$index' given");
		}
		
		return $this->all_forms[$index];
	}
	
	protected function createWordForm($word, $form_no, $ancode) {
		if(!isset($this->common_ancode_grammems)) {
			$common_ancode = $this->annot[0]['common_ancode'];
			
			$this->common_ancode_grammems = isset($common_ancode) ?
				$this->helper->getGrammems($common_ancode) :
				array();
		}
		
		list($pos_id, $all_grammems) = $this->helper->getGrammemsAndPartOfSpeech($ancode);
		
		return new phpMorphy_WordForm($word, $form_no, $pos_id, array_merge($this->common_ancode_grammems, $all_grammems));
	}
	
	protected function readAllForms() {
		if(!isset($this->all_forms)) {
			$result = array();
			
			$form_no = 0;
            
            $found_form_no = array();
			foreach($this->helper->getAllFormsWithAncodes($this->word, $this->annot, $found_form_no) as $form) {
                $word = $form[0];
                
				$result[] = $this->createWordForm($word, $form_no, $form[1]);
                
				$form_no++;
			}
			
            $this->found_form_no = $found_form_no[0];
			$this->all_forms = $result;
		}
		
		return $this->all_forms;
	}
	
    protected function getFoundFormNoLow() {
        $this->readAllForms();
        
        return $this->found_form_no['low'];
    }
    
    protected function getFoundFormNoHigh() {
        $this->readAllForms();
        
        return $this->found_form_no['high'];
    }
    
	function getFoundWordForm() {
		$result = array();
		for($i = $this->getFoundFormNoLow(), $c = $this->getFoundFormNoHigh() + 1; $i < $c; $i++) {
			$result[] = $this->getWordForm($i);
		}
		
		return $result;
	}
    
    function hasGrammems($grammems) {
        settype($grammems, 'array');
        
        foreach($this as $wf) {
            if($wf->hasGrammems($grammems)) {
                return true;
            }
        }
        
        return false;
    }
    
    function getWordFormsByGrammems($grammems) {
        settype($grammems, 'array');
        $result = array();
        
        foreach($this as $wf) {
            if($wf->hasGrammems($grammems)) {
                $result[] = $wf;
            }
        }
        
        return $result;
//        return count($result) ? $result : false;
    }
    
    function hasPartOfSpeech($poses) {
        settype($poses, 'array');
        
        foreach($this as $wf) {
            if(in_array($wf->getPartOfSpeech(), $poses, true)) {
                return true;
            }
        }
        
        return false;
    }
    
    function getWordFormsByPartOfSpeech($poses) {
        settype($poses, 'array');
        $result = array();
        
        foreach($this as $wf) {
            if(in_array($wf->getPartOfSpeech(), $poses, true)) {
                $result[] = $wf;
            }
        }
        
        return $result;
//        return count($result) ? $result : false;
    }
	
	function count() {
		return count($this->readAllForms());
	}
	
	function offsetExists($off) {
		$this->readAllForms();
		
		return isset($this->all_forms[$off]);
	}
	
	function offsetSet($off, $value) {
		throw new phpMorphy_Exception(__CLASS__ . " is not mutable");
	}
	
	function offsetUnset($off) {
		throw new phpMorphy_Exception(__CLASS__ . " is not mutable");
	}
	
	function offsetGet($off) {
		return $this->getWordForm($off);
	}
	
	function getIterator() {
		$this->readAllForms();
		
		return new ArrayIterator($this->all_forms);
	}
}

// ----------------------------
// Finders
// ----------------------------
interface phpMorphy_Morphier_Finder_Interface {
    function findWord($word);
    function decodeAnnot($raw, $withBase);
    function getAnnotDecoder();
}

abstract class phpMorphy_Morphier_Finder_Base implements phpMorphy_Morphier_Finder_Interface {
    protected
        $annot_decoder,
        $prev_word,
        $prev_result = false;
    
    function __construct(phpMorphy_AnnotDecoder_Interface $annotDecoder) {
        $this->annot_decoder = $annotDecoder;
    }
    
    function findWord($word) {
        if($this->prev_word === $word) {
            return $this->prev_result;
        }
        
        $result = $this->doFindWord($word);
        
        $this->prev_word = $word;
        $this->prev_result = $result;
        
        return $result;
    }
    
    function getAnnotDecoder() {
        return $this->annot_decoder;
    }
    
    function decodeAnnot($raw, $withBase) {
        return $this->annot_decoder->decode($raw, $withBase);
    }
    
    abstract protected function doFindWord($word);
}

class phpMorphy_Morphier_Finder_Common extends phpMorphy_Morphier_Finder_Base {
	protected
    	$fsa,
    	$root;

    function __construct(phpMorphy_Fsa_Interface $fsa, phpMorphy_AnnotDecoder_Interface $annotDecoder) {
        parent::__construct($annotDecoder);

        $this->fsa = $fsa;
        $this->root = $this->fsa->getRootTrans();
    }

    protected function doFindWord($word) {
        if(false === ($result = $this->fsa->walk($this->root, $word, true)) || !$result['annot']) {
            return false;
        }
        
        return $result['annot'];
    }
}

class phpMorphy_Morphier_Finder_Predict_Suffix extends phpMorphy_Morphier_Finder_Common {
    protected
        $min_suf_len,
        $unicode;
    
    function __construct(phpMorphy_Fsa_Interface $fsa, phpMorphy_AnnotDecoder_Interface $annotDecoder, $encoding, $minimalSuffixLength = 4) {
        parent::__construct($fsa, $annotDecoder);
        
        $this->min_suf_len = (int)$minimalSuffixLength;
        $this->unicode = phpMorphy_UnicodeHelper::create($encoding);
    }
    
    protected function doFindWord($word) {
        $word_len = $this->unicode->strlen($word);
        
        if(!$word_len) {
            return false;
        }
        
        for($i = 1, $c = $word_len - $this->min_suf_len; $i < $c; $i++) {
            $word = substr($word, $this->unicode->firstCharSize($word));
            
            if(false !== ($result = parent::doFindWord($word))) {
                break;
            }
        }

        if($i < $c) {
            //$known_len = $word_len - $i;
            $unknown_len = $i;
            
            return $result;
            /*
            return $this->fixAnnots(
                $this->decodeAnnot($result, true),
                $unknown_len
            );
            */
        } else {
            return false;
        }
    }
    
    protected function fixAnnots($annots, $len) {
        for($i = 0, $c = count($annots); $i < $c; $i++) {
            $annots[$i]['cplen'] = $len;
        }
        
        return $annots;
    }
}

class phpMorphy_Morphier_PredictCollector extends phpMorphy_Fsa_WordsCollector {
    protected
        $used_poses = array(),
        $annot_decoder,
        $collected = 0;
    
    function __construct($limit, phpMorphy_AnnotDecoder_Interface $annotDecoder) {
        parent::__construct($limit);
        
        $this->annot_decoder = $annotDecoder;
    }
    
    function collect($path, $annotRaw) {
        if($this->collected > $this->limit) {
            return false;
        }
        
        $used_poses =& $this->used_poses;
        $annots = $this->decodeAnnot($annotRaw);
        
        for($i = 0, $c = count($annots); $i < $c; $i++) {
            $annot = $annots[$i];
            $annot['cplen'] = $annot['plen'] = 0;
            
            $pos_id = $annot['pos_id'];
            
            if(isset($used_poses[$pos_id])) {
                $result_idx = $used_poses[$pos_id];
                
                if($annot['freq'] > $this->items[$result_idx]['freq']) {
                    $this->items[$result_idx] = $annot;
                }
            } else {
                $used_poses[$pos_id] = count($this->items);
                $this->items[] = $annot;
            }
        }
        
        $this->collected++;
        return true;
    }
    
    function clear() {
        parent::clear();
        $this->collected = 0;
        $this->used_poses = array();
    }
    
    function decodeAnnot($annotRaw) {
        return $this->annot_decoder->decode($annotRaw, true);
    }
}

class phpMorphy_Morphier_Finder_Predict_Databse extends phpMorphy_Morphier_Finder_Common {
    protected
        $collector,
        $unicode,
        $graminfo,
        $min_postfix_match;
    
    function __construct(
    	phpMorphy_Fsa_Interface $fsa,
    	phpMorphy_AnnotDecoder_Interface $annotDecoder,
    	$encoding,
        phpMorphy_GramInfo_Interace $graminfo,
        $minPostfixMatch = 2,
        $collectLimit = 32
    ) {
        parent::__construct($fsa, $annotDecoder);
        
        $this->graminfo = $graminfo;
        $this->min_postfix_match = $minPostfixMatch;
        $this->collector = $this->createCollector($collectLimit, $this->getAnnotDecoder());
        
        $this->unicode = phpMorphy_UnicodeHelper::create($encoding);
    }
    
    protected function createAnnotDecoder() {
        return phpmorphy_annot_decoder_new('predict');
    }
    
    protected function doFindWord($word) {
        $rev_word = $this->unicode->strrev($word);
        $result = $this->fsa->walk($this->root, $rev_word);
        
        if($result['result'] && null !== $result['annot']) {
            $annots = $result['annot'];
        } else {
            $match_len = $this->unicode->strlen($this->unicode->fixTrailing(substr($rev_word, 0, $result['walked'])));
            
            if(null === ($annots = $this->determineAnnots($result['last_trans'], $match_len))) {
                return false;
            }
        }
        
        if(!is_array($annots)) {
            $annots = $this->collector->decodeAnnot($annots);
        }
        
        return $this->fixAnnots($word, $annots);
    }
    
    protected function determineAnnots($trans, $matchLen) {
        $annots = $this->fsa->getAnnot($trans);
        
        if(null == $annots && $matchLen >= $this->min_postfix_match) {
            $this->collector->clear();
            
            $this->fsa->collect(
                $trans,
                $this->collector->getCallback()
            );
            
            $annots = $this->collector->getItems();
        }
        
        return $annots;
    }
    
    protected function fixAnnots($word, $annots) {
        $result = array();
        
        // remove all prefixes?
        for($i = 0, $c = count($annots); $i < $c; $i++) {
            $annot = $annots[$i];
            
            $annot['cplen'] = $annot['plen'] = 0;
            
            $flexias = $this->graminfo->readFlexiaData($annot, false);
            
            $prefix = $flexias[$annot['form_no'] * 2];
            $suffix = $flexias[$annot['form_no'] * 2 + 1];
            
            $plen = strlen($prefix);
            $slen = strlen($suffix);
            if(
                (!$plen || substr($word, 0, strlen($prefix)) === $prefix) &&
                (!$slen || substr($word, -strlen($suffix)) === $suffix)
            ) {
                $result[] = $annot;
            }
        }

        return count($result) ? $result : false;
    }
    
    protected function createCollector($limit) {
        return new phpMorphy_Morphier_PredictCollector($limit, $this->getAnnotDecoder());
    }
}

// ----------------------------
// Morphiers
// ----------------------------
abstract class phpMorphy_Morphier_Base implements phpMorphy_Morphier_Interface {
	protected
		$finder,
		$helper,
        $gramtab_consts_included = false;
    
    function __construct(phpMorphy_Morphier_Finder_Interface $finder, phpMorphy_Morphier_Helper $helper) {
        $this->finder = $finder;
        
        $this->helper = clone $helper;
        $this->helper->setAnnotDecoder($finder->getAnnotDecoder());
    }
    
    function getFinder() {
        return $this->finder;
    }
    
    function getHelper() {
        return $this->helper();
    }
    
	protected function createWordDescriptor($word, $annots) {
        if(!$this->gramtab_consts_included) {
            $this->includeGramTabConsts();
        }
        
		return new phpMorphy_WordDescriptor_Collection($word, $annots, $this->helper);
	}
    
    function includeGramTabConsts() {
        if($this->helper->isResolvePartOfSpeech()) {
            $this->helper->getGramTab()->includeConsts();
        }
        
        $this->gramtab_consts_included = true;
    }
	
	function getWordDescriptor($word) {
		if(false === ($annots = $this->finder->findWord($word))) {
			return false;
		}
		
		return $this->createWordDescriptor($word, $annots, $this->helper);
	}

	function getPartOfSpeech($word) {
		if(false === ($annots = $this->finder->findWord($word))) {
			return false;
		}
		
		return $this->helper->getPartOfSpeech($word, $annots);
	}
	
	function getBaseForm($word) {
		if(false === ($annots = $this->finder->findWord($word))) {
			return false;
		}

		return $this->helper->getBaseForm($word, $annots);
	}
	
	function getPseudoRoot($word) {
		if(false === ($annots = $this->finder->findWord($word))) {
			return false;
		}

		return $this->helper->getPseudoRoot($word, $annots);
	}
	
	function getAllForms($word) {
		if(false === ($annots = $this->finder->findWord($word))) {
			return false;
		}

		return $this->helper->getAllForms($word, $annots);
	}
};

class phpMorphy_Morphier_Common extends phpMorphy_Morphier_Base {
    function __construct(phpMorphy_Fsa_Interface $fsa, phpMorphy_Morphier_Helper $helper) {
        parent::__construct(
            new phpMorphy_Morphier_Finder_Common(
            	$fsa, 
                $this->createAnnotDecoder($helper)
            ),
            $helper
        );
    }
    
    protected function createAnnotDecoder(phpMorphy_Morphier_Helper $helper) {
        return phpMorphy_AnnotDecoder_Factory::create($helper->getGramInfo()->getEnds())->getCommonDecoder();
    }
};

class phpMorphy_Morphier_Predict_Suffix extends phpMorphy_Morphier_Base {
    function __construct(phpMorphy_Fsa_Interface $fsa, phpMorphy_Morphier_Helper $helper) {
        parent::__construct(
            new phpMorphy_Morphier_Finder_Predict_Suffix(
            	$fsa,
                $this->createAnnotDecoder($helper),
                $helper->getGramInfo()->getEncoding(),
                4
            ),
            $helper
        );
    }

    protected function createAnnotDecoder(phpMorphy_Morphier_Helper $helper) {
        return phpMorphy_AnnotDecoder_Factory::create($helper->getGramInfo()->getEnds())->getCommonDecoder();
    }
}

class phpMorphy_Morphier_Predict_Database extends phpMorphy_Morphier_Base {
    function __construct(phpMorphy_Fsa_Interface $fsa, phpMorphy_Morphier_Helper $helper) {
        parent::__construct(
            new phpMorphy_Morphier_Finder_Predict_Databse(
                $fsa,
                $this->createAnnotDecoder($helper),
                $helper->getGramInfo()->getEncoding(),
                $helper->getGramInfo(),
                2,
                32
            ),
            $helper
        );
    }
    
    protected function createAnnotDecoder(phpMorphy_Morphier_Helper $helper) {
        return phpMorphy_AnnotDecoder_Factory::create($helper->getGramInfo()->getEnds())->getPredictDecoder();
    }
}

class phpMorphy_Morphier_Bulk {
	protected
        $fsa,
        $root_trans,
        $helper,
        $graminfo;
	
	function __construct(phpMorphy_Fsa_Interface $fsa, phpMorphy_Morphier_Helper $helper) {
        $this->fsa = $fsa;
        $this->root_trans = $fsa->getRootTrans();
        
        $this->helper = clone $helper;
        $this->helper->setAnnotDecoder($this->createAnnotDecoder($helper));
        
		$this->graminfo = $helper->getGramInfo();
	}
    
    protected function createAnnotDecoder(phpMorphy_Morphier_Helper $helper) {
        return new phpMorphy_AnnotDecoder_Common($helper->getGramInfo()->getEnds());
    }
	
	function getBaseForm($words, &$notfound) {
		$annots = $this->findWord($words, $notfound);
		
		return $this->composeForms($annots, true, false, false);
	}
	
	function getAllForms($words, &$notfound) {
		$annots = $this->findWord($words, $notfound);
		
		return $this->composeForms($annots, false, false, false);
	}
	
	function getPseudoRoot($words, &$notfound) {
		$annots = $this->findWord($words, $notfound);
		
		return $this->composeForms($annots, false, true, false);
	}
	
	function getPartOfSpeech($words, &$notfound) {
		$annots = $this->findWord($words, $notfound);
		
		return $this->composeForms($annots, false, false, true);
	}
	
	protected function findWord($words, &$notfound) {
		$unknown_words_annot = '';
		
		list($labels, $finals, $dests) = $this->buildPatriciaTrie($words);
		
		$annots = array();
		$unknown_words_annot = '';
		$stack = array(0, '', $this->root_trans);
		$stack_idx = 0;
		
		$fsa = $this->fsa;
		
		// TODO: Improve this
		while($stack_idx >= 0) {
			$n = $stack[$stack_idx];
			$path = $stack[$stack_idx + 1] . $labels[$n];
			$trans = $stack[$stack_idx + 2];
			$stack_idx -= 3; // TODO: Remove items from stack? (performance!!!)
			
			$is_final = $finals[$n] > 0;
			
			$result = false;
			if(false !== $trans && $n > 0) {
				$label = $labels[$n];
				
				$result = $fsa->walk($trans, $label, $is_final);
				
				if(strlen($label) == $result['walked']) {
					$trans = $result['word_trans'];
				} else {
					$trans = false;
				}
			}
			
			if($is_final) {
				if(false !== $trans && isset($result['annot'])) {
					$annots[$result['annot']][] = $path;
				} else {
					//$annots[$unknown_words_annot][] = $path;
					$notfound[] = $path;
				}
			}
			
			if(false !== $dests[$n]) {
				foreach($dests[$n] as $dest) {
					$stack_idx += 3;
					$stack[$stack_idx] = $dest;
					$stack[$stack_idx + 1] = $path;
					$stack[$stack_idx + 2] = $trans;
				}
			}
		}
		
		return $annots;
	}
	
	protected function composeForms($annotsRaw, $onlyBase, $pseudoRoot, $partOfSpeech) {
		$result = array();

		// process found annotations
		foreach($annotsRaw as $annot_raw => $words) {
			if(strlen($annot_raw) == 0) continue;
			
			foreach($this->helper->decodeAnnot($annot_raw, $onlyBase) as $annot) {
				if(!($onlyBase || $pseudoRoot)) {
					$flexias = $this->graminfo->readFlexiaData($annot);
				}
				
				$cplen = $annot['cplen'];
				$plen = $annot['plen'];
				$flen = $annot['flen'];
				
				if($partOfSpeech) {
					$pos_id = $this->helper->extractPartOfSpeech($annot);
				}
				
				foreach($words as $word) {
					if($flen) {
						$base = substr($word, $cplen + $plen, -$flen);
					} else {
						if($cplen || $plen) {
							$base = substr($word, $cplen + $plen);
						} else {
							$base = $word;
						}
					}
					
					$prefix = $cplen ? substr($word, 0, $cplen) : '';
					
					if($pseudoRoot) {
						$result[$word][$base] = 1;
					} else if($onlyBase) {
						$form = $prefix . $annot['base_prefix'] . $base . $annot['base_suffix'];
						
						$result[$word][$form] = 1;
					} else if($partOfSpeech) {
						$result[$word][$pos_id] = 1;
					} else {
						for($i = 0, $c = count($flexias); $i < $c; $i += 2) {
							$form = $prefix . $flexias[$i] . $base . $flexias[$i + 1];
							$result[$word][$form] = 1;
						}
					}
				}
			}
		}
		
		for($keys = array_keys($result), $i = 0, $c = count($result); $i < $c; $i++) {
			$key = $keys[$i];
			
			$result[$key] = array_keys($result[$key]);
		}
		
		return $result;
	}
	
	protected function buildPatriciaTrie($words) {
		sort($words);
		
		$stack = array();
		$prev_word = '';
		$prev_word_len = 0;
		$prev_lcp = 0;
		
		$state_labels = array();
		$state_finals = array();
		$state_dests = array();
		
		$state_labels[] = '';
		$state_finals = '0';
		$state_dests[] = array();
		
		$node = 0;
		
		foreach($words as $word) {
			if($word == $prev_word) {
				continue;
			}
			
			$word_len = strlen($word);
			// find longest common prefix
			for($lcp = 0, $c = min($prev_word_len, $word_len); $lcp < $c && $word[$lcp] == $prev_word[$lcp]; $lcp++);
			
			if($lcp == 0) {
				$stack = array();
				
				$new_state_id = count($state_labels);
				
				$state_labels[] = $word;
				$state_finals .= '1';
				$state_dests[] = false;
				
				$state_dests[0][] = $new_state_id;
				
				$node = $new_state_id;
			} else {
				$need_split = true;
				$trim_size = 0; // for split
				
				if($lcp == $prev_lcp) {
					$need_split = false;
					$node = $stack[count($stack) - 1];
				} elseif($lcp > $prev_lcp) {
					if($lcp == $prev_word_len) {
						$need_split = false;
					} else {
						$need_split = true;
						$trim_size = $lcp - $prev_lcp;
					}
					
					$stack[] = $node;
				} else {
					$trim_size = strlen($prev_word) - $lcp;
					
					for($stack_size = count($stack) - 1; ;--$stack_size) {
						$trim_size -= strlen($state_labels[$node]);
						
						if($trim_size <= 0) {
							break;
						}
						
						if(count($stack) < 1) {
							throw new phpMorphy_Exception('Infinite loop posible');
						}
						
						$node = array_pop($stack);
					}
					
					$need_split = $trim_size < 0;
					$trim_size = abs($trim_size);
					
					if($need_split) {
						$stack[] = $node;
					} else {
						$node = $stack[$stack_size];
					}
				}
				
				if($need_split) {
					$node_key = $state_labels[$node];
					
					// split
					$new_node_id_1 = count($state_labels);
					$new_node_id_2 = $new_node_id_1 + 1;
					
					// new_node_1
					$state_labels[] = substr($node_key, $trim_size);
					$state_finals .= $state_finals[$node];
					$state_dests[] = $state_dests[$node];
					
					// adjust old node
					$state_labels[$node] = substr($node_key, 0, $trim_size);
					$state_finals[$node] = '0';
					$state_dests[$node] = array($new_node_id_1);
					
					// append new node, new_node_2
					$state_labels[] = substr($word, $lcp);
					$state_finals .= '1';
					$state_dests[] = false;
	
					$state_dests[$node][] = $new_node_id_2;
					
					$node = $new_node_id_2;
				} else {
					$new_node_id = count($state_labels);
					
					$state_labels[] = substr($word, $lcp);
					$state_finals .= '1';
					$state_dests[] = false;
					
					if(false !== $state_dests[$node]) {
						$state_dests[$node][] = $new_node_id;
					} else {
						$state_dests[$node] = array($new_node_id);
					}
					
					$node = $new_node_id;
				}
			}
			
			$prev_word = $word;
			$prev_word_len = $word_len;
			$prev_lcp = $lcp;
		}
		
		return array($state_labels, $state_finals, $state_dests);
	}
}
