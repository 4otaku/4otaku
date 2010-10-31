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

if(!defined('PHPMORPHY_DIR')) {
	define('PHPMORPHY_DIR', dirname(__FILE__));
}

require_once(PHPMORPHY_DIR . '/fsa/fsa.php');
require_once(PHPMORPHY_DIR . '/graminfo/graminfo.php');
require_once(PHPMORPHY_DIR . '/morphiers.php');
require_once(PHPMORPHY_DIR . '/gramtab.php');
require_once(PHPMORPHY_DIR . '/storage.php');
require_once(PHPMORPHY_DIR . '/source.php');

class phpMorphy_Exception extends Exception { }

class phpMorphy_FilesBundle {
	protected
		$dir,
		$lang;

	function phpMorphy_FilesBundle($dirName, $lang) {
		$this->dir = rtrim($dirName, '/\\' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->setLang($lang);
	}

	function getLang() {
		return $this->lang;
	}

	function setLang($lang) {
		$this->lang = strtolower($lang);
	}

	function getCommonAutomatFile() {
		return $this->genFileName('common_aut');
	}

	function getPredictAutomatFile() {
		return $this->genFileName('predict_aut');
	}

	function getGramInfoFile() {
		return $this->genFileName('morph_data');
	}
	
	function getGramTabFile() {
		return $this->genFileName('gramtab');
	}

	function getGramTabFileWithTextIds() {
		return $this->genFileName('gramtab_txt');
	}
    
    function getDbaFile($type) {
        if(!isset($type)) {
            $type = 'db3';
        }
        
        return $this->genFileName("common_dict_$type");
    }
    
    function getGramInfoHeaderCacheFile() {
		return $this->genFileName('morph_data_header_cache');
    }
    
	protected function genFileName($token, $extraExt = null) {
		return $this->dir . $token . '.' . $this->lang . (isset($extraExt) ? '.' . $extraExt : '') . '.bin';
	}
};

class phpMorphy_WordDescriptor_Collection_Serializer {
    function serialize(phpMorphy_WordDescriptor_Collection $collection) {
        $result = array();
        
        foreach($collection as $descriptor) {
            $result[] = $this->processWordDescriptor($descriptor);
        }
        
        return $result;
    }
    
    protected function processWordDescriptor(phpMorphy_WordDescriptor $descriptor) {
        $forms = array();
        $all = array();
        
        foreach($descriptor as $word_form) {
            $forms[] = $word_form->getWord();
            $all[] = $this->serializeGramInfo($word_form);
        }
        
        return array(
            'forms' => $forms,
            'all' => $all,
            'common' => '',
        );
    }
    
    protected function serializeGramInfo(phpMorphy_WordForm $wordForm) {
        return $wordForm->getPartOfSpeech() . ' ' . implode(',', $wordForm->getGrammems());
    }
}

class phpMorphy {
	const NORMAL = 0;
	const IGNORE_PREDICT = 2;
	const ONLY_PREDICT = 3;
	
	const PREDICT_BY_NONE = 'none';
	const PREDICT_BY_SUFFIX = 'by_suffix';
	const PREDICT_BY_DB = 'by_db';
	
	protected
		$__storage_factory,
		$__common_fsa,
        $__common_source,
		$__predict_fsa,
		$__options,
		//$__common_morphier,
		//$__predict_by_suf_morphier,
		//$__predict_by_db_morphier,
		//$__bulk_morphier,
        //$__word_descriptor_serializer,
		$__helper,
		$__last_prediction_type
		;
	
	function __construct($dir, $lang = null, $options = array()) {
		$this->options = $options = $this->repairOptions($options);
		
		// TODO: use two versions of phpMorphy class i.e. phpMorphy_v3 { } ... phpMorphy_v2 extends phpMorphy_v3
		if($dir instanceof phpMorphy_FilesBundle && is_array($lang)) {
			$this->initOldStyle($dir, $lang);
		} else {
			$this->initNewStyle($this->createFilesBundle($dir, $lang), $options);
		}
		
		$this->__last_prediction_type = self::PREDICT_BY_NONE;
	}
    
    /**
    * @return phpMorphy_Morphier_Interface
    */
    function getCommonMorphier() {
        return $this->__common_morphier;
    }
    
    /**
    * @return phpMorphy_Morphier_Interface
    */
    function getPredictBySuffixMorphier() {
        return $this->__predict_by_suf_morphier;
    }
    
    /**
    * @return phpMorphy_Morphier_Interface
    */
    function getPredictByDatabaseMorphier() {
        return $this->__predict_by_db_morphier;
    }
    
    /**
    * @return phpMorphy_Morphier_Bulk
    */
    function getBulkMorphier() {
        return $this->__bulk_morphier;
    }
	
    /**
    * @return string
    */
	function getEncoding() {
		return $this->__helper->getGramInfo()->getEncoding();
	}
	
    /**
    * @return string
    */
	function getLocale() {
		return $this->__helper->getGramInfo()->getLocale();
	}
	
    /**
    * @return phpMorphy_Shm_Cache
    */
	function getShmCache() {
		return $this->__storage_factory->getShmCache();
	}
	
    /**
    * @return bool
    */
	function isLastPredicted() {
		return self::PREDICT_BY_NONE !== $this->__last_prediction_type;
	}
	
	function getLastPredicitionType() {
		return $this->__last_prediction_type;
	}
	
	/**
	* @param mixed $word - string or array of strings
	* @param mixed $type - prediction managment
	* @return phpMorphy_WordDescriptor_Collection
	*/
    function findWord($word, $type = self::NORMAL) {
        if(is_array($word)) {
            $result = array();
            
            foreach($word as $w) {
                $result[$w] = $this->invoke('getWordDescriptor', $w, $type);
            }
            
            return $result;
        } else {
            return $this->invoke('getWordDescriptor', $word, $type);
        }
    }
    
    /**
    * Alias for getBaseForm
    * 
	* @param mixed $word - string or array of strings
	* @param mixed $type - prediction managment
	* @return array
    */
	function lemmatize($word, $type = self::NORMAL) {
		return $this->getBaseForm($word, $type);
	}
	
    /**
	* @param mixed $word - string or array of strings
	* @param mixed $type - prediction managment
	* @return array
    */
	function getBaseForm($word, $type = self::NORMAL) {
		return $this->invoke('getBaseForm', $word, $type);
	}
	
    /**
	* @param mixed $word - string or array of strings
	* @param mixed $type - prediction managment
	* @return array
    */
	function getAllForms($word, $type = self::NORMAL) {
		return $this->invoke('getAllForms', $word, $type);
	}
	
    /**
	* @param mixed $word - string or array of strings
	* @param mixed $type - prediction managment
	* @return array
    */
	function getPseudoRoot($word, $type = self::NORMAL) {
		return $this->invoke('getPseudoRoot', $word, $type);
	}
	
    /**
	* @param mixed $word - string or array of strings
	* @param mixed $type - prediction managment
	* @return array
    */
	function getPartOfSpeech($word, $type = self::NORMAL) {
		return $this->invoke('getPartOfSpeech', $word, $type);
	}
    
    /**
	* @param mixed $word - string or array of strings
	* @param mixed $type - prediction managment
	* @return array
    */
    function getAllFormsWithGramInfo($word, $type = self::NORMAL) {
        if(false === ($result = $this->findWord($word, $type))) {
            return false;
        }
        
        if(is_array($word)) {
            $out = array();
            
            foreach($result as $w => $r) {
                if(false !== $r) {
                    $out[$w] = $this->processWordsCollection($r);
                } else {
                    $out[$w] = false;
                }
            }
            
            return $out;
        } else {
            return $this->processWordsCollection($result);
        }
    }
    
    
    // public interface end
    
    protected function processWordsCollection(phpMorphy_WordDescriptor_Collection $collection) {
        return $this->__word_descriptor_serializer->serialize($collection);
    }
	
	protected function invoke($method, $word, $type) {
		$this->__last_prediction_type = self::PREDICT_BY_NONE;
		
		if($type === self::ONLY_PREDICT) {
			if(is_array($word)) {
				$result = array();
				
				foreach($word as $w) {
					$result[$w] = $this->predictWord($method, $w);
				}
				
				return $result;
			} else {
				return $this->predictWord($method, $word);
			}
		}
		
		if(is_array($word)) {
			$not_found = array();
			
			$result = $this->__bulk_morphier->$method($word, $not_found);
			
			if($type !== self::IGNORE_PREDICT) {
				for($i = 0, $c = count($not_found); $i < $c; $i++) {
					$word = $not_found[$i];
					
					$result[$word] = $this->predictWord($method, $word);
				}
			} else {
				for($i = 0, $c = count($not_found); $i < $c; $i++) {
					$result[$not_found[$i]] = false;
				}
			}
			
			return $result;
		} else {
			if(false === ($result = $this->__common_morphier->$method($word))) {
				if($type !== self::IGNORE_PREDICT) {
					return $this->predictWord($method, $word);
				}
			}
			
			return $result;
		}
	}
	
	protected function predictWord($method, $word) {
		if(false !== ($result = $this->__predict_by_suf_morphier->$method($word))) {
			$this->__last_prediction_type = self::PREDICT_BY_SUFFIX;
			
			return $result;
		}
		
		if(false !== ($result = $this->__predict_by_db_morphier->$method($word))) {
			$this->__last_prediction_type = self::PREDICT_BY_DB;
			
			return $result;
		}
		
		return false;
	}
	
	////////////////
	// init code
	////////////////
	protected function initNewStyle(phpMorphy_FilesBundle $bundle, $options) {
		$this->__options = $options = $this->repairOptions($options);
        $storage_type = $options['storage'];
        
		$storage_factory = $this->__storage_factory = $this->createStorageFactory($options['shm']);
		$graminfo_as_text = $this->options['graminfo_as_text'];
		
        // fsa
		$this->__common_fsa = $this->createFsa($storage_factory->open($storage_type, $bundle->getCommonAutomatFile(), false), false); // lazy
		$this->__predict_fsa = $this->createFsa($storage_factory->open($storage_type, $bundle->getPredictAutomatFile(), true), true);  // lazy

        // graminfo
		$graminfo = $this->createGramInfo($storage_factory->open($storage_type, $bundle->getGramInfoFile(), true), $bundle); // lazy
		
        // gramtab
		$gramtab = $this->createGramTab(
			$storage_factory->open(
				$storage_type,
				$graminfo_as_text ? $bundle->getGramTabFileWithTextIds() : $bundle->getGramTabFile(),
				true
			)
		); // always lazy
		
        // common source
        //$this->__common_source = $this->createCommonSource($bundle, $this->options['common_source']);
        
		$this->__helper = $this->createMorphierHelper($graminfo, $gramtab, $graminfo_as_text);
	}
	
    protected function createCommonSource(phpMorphy_FilesBundle $bundle, $opts) {
        $type = $opts['type'];
        
        switch($type) {
            case PHPMORPHY_SOURCE_FSA:
                return new phpMorphy_Source_Fsa($this->__common_fsa);
            case PHPMORPHY_SOURCE_DBA:
                return new phpMorphy_Source_Dba(
                    $bundle->getDbaFile($this->getDbaHandlerName(@$opts['opts']['handler'])),
                        $opts['opts']
                    );
            default:
                throw new phpMorphy_Exception("Unknown source type given '$type'");
        }
    }
    
    protected function getDbaHandlerName($name) {
        return isset($name) ? $name : phpMorphy_Source_Dba::getDefaultHandler();
    }
    
	protected function initOldStyle(phpMorphy_FilesBundle $bundle, $options) {
		$options = $this->repairOptions($options);
		
		switch($bundle->getLang()) {
			case 'rus':
				$bundle->setLang('ru_RU');
				break;
			case 'eng':
				$bundle->setLang('en_EN');
				break;
			case 'ger':
				$bundle->setLang('de_DE');
				break;
		}
		
		$this->initNewStyle($bundle, $options);
	}
	
	protected function repairOldOptions($options) {
		$defaults = array(
			'predict_by_suffix' => false,
			'predict_by_db' => false,
		);
		
		return (array)$options + $defaults;
	}
	
    protected function repairSourceOptions($options) {
        $defaults = array(
            'type' => PHPMORPHY_SOURCE_FSA,
            'opts' => null
        );
        
        return (array)$options + $defaults;
    }
    
	protected function repairOptions($options) {
		$defaults = array(
			'shm' => array(),
			'graminfo_as_text' => true,
            'storage' => PHPMORPHY_STORAGE_FILE,
            'common_source' => $this->repairSourceOptions(@$options['common_source']),
			'predict_by_suffix' => true,
			'predict_by_db' => true,
		);
		
		return (array)$options + $defaults;
	}
	
	function __get($name) {
		switch($name) {
			case '__predict_by_db_morphier':
				$this->__predict_by_db_morphier = $this->createPredictByDbMorphier(
					$this->__predict_fsa,
					$this->__helper
				);
				
				break;
			case '__predict_by_suf_morphier':
				$this->__predict_by_suf_morphier = $this->createPredictBySuffixMorphier(
					$this->__common_fsa,
					$this->__helper
				);
				
				break;
			case '__bulk_morphier':
				$this->__bulk_morphier = $this->createBulkMorphier(
					$this->__common_fsa,
					$this->__helper
				);
				
				break;
			case '__common_morphier':
				$this->__common_morphier = $this->createCommonMorphier(
					$this->__common_fsa,
					$this->__helper
				);
				
				break;
            
            case '__word_descriptor_serializer':
                $this->__word_descriptor_serializer = $this->createWordDescriptorSerializer();
                break;
			default:
				throw new phpMorphy_Exception("Invalid prop name '$name'");
		}
		
		return $this->$name;
	}
	
	////////////////////
	// factory methods
	////////////////////
    protected function createWordDescriptorSerializer() {
        return new phpMorphy_WordDescriptor_Collection_Serializer();
    }
    
	protected function createFilesBundle($dir, $lang) {
		return new phpMorphy_FilesBundle($dir, $lang);
	}
	
	protected function createStorageFactory($options) {
		return new phpMorphy_Storage_Factory($options);
	}
	
	protected function createFsa(phpMorphy_Storage $storage, $lazy) {
    	return phpMorphy_Fsa::create($storage, $lazy);
	}
	
	protected function createGramInfo(phpMorphy_Storage $storage, phpMorphy_FilesBundle $bundle) {
		//return new phpMorphy_GramInfo_RuntimeCaching(new phpMorphy_GramInfo_Proxy($storage));
		//return new phpMorphy_GramInfo_RuntimeCaching(phpMorphy_GramInfo::create($storage, false));
		
		return new phpMorphy_GramInfo_RuntimeCaching(
			new phpMorphy_GramInfo_Proxy_WithHeader(
				$storage,
				$bundle->getGramInfoHeaderCacheFile()
			)
		);
	}
	
	protected function createGramTab(phpMorphy_Storage $storage) {
		return new phpMorphy_GramTab_Proxy($storage);
	}
	
	protected function createMorphierHelper(phpMorphy_GramInfo_Interace $graminfo, phpMorphy_GramTab_Interface $gramtab, $resolvePartOfSpeech) {
		return new phpMorphy_Morphier_Helper($graminfo, $gramtab, $resolvePartOfSpeech);
	}
	
	protected function createCommonMorphier(phpMorphy_Fsa_Interface $fsa, phpMorphy_Morphier_Helper $helper) {
		return new phpMorphy_Morphier_Common($fsa, $helper);
	}
	
	protected function createBulkMorphier(phpMorphy_Fsa_Interface $fsa, phpMorphy_Morphier_Helper $helper) {
		if($this->__options)
		return new phpMorphy_Morphier_Bulk($fsa, $helper);
	}
	
	protected function createPredictByDbMorphier(phpMorphy_Fsa_Interface $fsa, phpMorphy_Morphier_Helper $helper) {
		if($this->__options['predict_by_db']) {
				return new phpMorphy_Morphier_Predict_Database($fsa, $helper);
		} else {
			return new phpMorphy_Morphier_Empty();
		}
	}
	
	protected function createPredictBySuffixMorphier(phpMorphy_Fsa_Interface $fsa, phpMorphy_Morphier_Helper $helper) {
		if($this->__options['predict_by_suffix']) {
			return new phpMorphy_Morphier_Predict_Suffix($fsa, $helper);
		} else {
			return new phpMorphy_Morphier_Empty();
		}
	}
};
