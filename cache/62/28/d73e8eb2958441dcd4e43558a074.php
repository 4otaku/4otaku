<?php

/* web/single/video.html */
class __TwigTemplate_6228d73e8eb2958441dcd4e43558a074 extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<div class=\"shell post\">
\t<div id=\"post-";
        // line 2
        echo (isset($context['id']) ? $context['id'] : null);
        echo "\">
\t\t<div class=\"innerwrap\">
\t\t\t<table width=\"100%\">
\t\t\t\t<tr>
\t\t\t\t\t<td align=\"left\">
\t\t\t\t\t\t<h2>
\t\t\t\t\t\t\t<a href=\"/post/";
        // line 8
        echo (isset($context['id']) ? $context['id'] : null);
        echo "\" title=\"";
        echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "title", array(), "any", false, 8);
        echo "\">
\t\t\t\t\t\t\t\t";
        // line 9
        echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "title", array(), "any", false, 9);
        echo "
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</h2>
\t\t\t\t\t</td>
\t\t\t\t\t<td align=\"right\" valign=\"top\">
\t\t\t\t\t\t<a href=\"/post/";
        // line 14
        echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "id", array(), "any", false, 14);
        echo "\">
\t\t\t\t\t\t\tКомментировать
\t\t\t\t\t\t</a>
\t\t\t\t\t\t";
        // line 17
        if ($this->getAttribute((isset($context['item']) ? $context['item'] : null), "comments", array(), "any", false, 17)) {
            // line 18
            echo "\t\t\t\t\t\t\t (";
            echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "comments", array(), "any", false, 18);
            echo ")
\t\t\t\t\t\t";
        }
        // line 20
        echo "\t\t\t\t\t</td>
\t\t\t\t</tr>
\t\t\t</table>
\t\t\t<div class=\"center clear\">
\t\t\t\t<center>
\t\t\t\t\t";
        // line 25
        echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "object", array(), "any", false, 25);
        echo "
\t\t\t\t</center>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"wrapper\">
\t\t\t<p class=\"meta\">
\t\t\t\t";
        // line 31
        echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "date", array(), "any", false, 31);
        echo " | 
\t\t\t\t";
        // line 32
        echo $this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta_header", array(), "any", false, 32), "author", array(), "any", false, 32);
        echo " 
\t\t\t\t";
        // line 33
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta", array(), "any", false, 33), "author", array(), "any", false, 33));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context['key'] => $context['author']) {
            // line 34
            echo "\t\t\t\t\t";
            if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 34))) {
                echo ", ";
            }
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t
\t\t\t\t\t<a href=\"/video/author/";
            // line 35
            echo $this->getAttribute((isset($context['author']) ? $context['author'] : null), "alias", array(), "any", false, 35);
            echo "/\">
\t\t\t\t\t\t";
            // line 36
            echo $this->getAttribute((isset($context['author']) ? $context['author'] : null), "name", array(), "any", false, 36);
            echo "
\t\t\t\t\t</a>
\t\t\t\t";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['author'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 38
        echo " | 
\t\t\t\t";
        // line 39
        echo $this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta_header", array(), "any", false, 39), "category", array(), "any", false, 39);
        echo " 
\t\t\t\t";
        // line 40
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta", array(), "any", false, 40), "category", array(), "any", false, 40));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context['key'] => $context['category']) {
            // line 41
            echo "\t\t\t\t\t";
            if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 41))) {
                echo ", ";
            }
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t
\t\t\t\t\t<a href=\"/video/author/";
            // line 42
            echo $this->getAttribute((isset($context['category']) ? $context['category'] : null), "alias", array(), "any", false, 42);
            echo "/\">
\t\t\t\t\t\t";
            // line 43
            echo $this->getAttribute((isset($context['category']) ? $context['category'] : null), "name", array(), "any", false, 43);
            echo "
\t\t\t\t\t</a>
\t\t\t\t";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['category'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 45
        echo " | 
\t\t\t\t";
        // line 46
        echo $this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta_header", array(), "any", false, 46), "tag", array(), "any", false, 46);
        echo " 
\t\t\t\t";
        // line 47
        if ($this->getAttribute((isset($context['item']) ? $context['item'] : null), "have_tag_variants", array(), "any", false, 47)) {
            // line 48
            echo "\t\t\t\t\t<span class=\"synonims\">
\t\t\t\t\t\t<a href=\"#\" class=\"disabled\" title=\"Показать синонимы\" rel=\"";
            // line 49
            echo (isset($context['id']) ? $context['id'] : null);
            echo "\">
\t\t\t\t\t\t\t&gt;&gt;
\t\t\t\t\t\t</a>
\t\t\t\t\t</span> 
\t\t\t\t";
        }
        // line 54
        echo "\t\t\t\t";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta", array(), "any", false, 54), "tag", array(), "any", false, 54));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context['key'] => $context['tag']) {
            // line 55
            echo "\t\t\t\t\t<a href=\"/video/tag/";
            echo $this->getAttribute((isset($context['tag']) ? $context['tag'] : null), "alias", array(), "any", false, 55);
            echo "/\">
\t\t\t\t\t\t";
            // line 56
            echo $this->getAttribute((isset($context['tag']) ? $context['tag'] : null), "name", array(), "any", false, 56);
            echo "
\t\t\t\t\t</a>
\t\t\t\t\t";
            // line 58
            if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "last", array(), "any", false, 58))) {
                echo ", ";
            }
            // line 59
            echo "\t\t\t\t\t<span class=\"hidden tag_synonims tag_synonims_";
            echo (isset($context['id']) ? $context['id'] : null);
            echo "\">
\t\t\t\t\t\t";
            // line 60
            if ($this->getAttribute((isset($context['tag']) ? $context['tag'] : null), "variants", array(), "any", false, 60)) {
                // line 61
                echo "\t\t\t\t\t\t\t";
                if ($this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "last", array(), "any", false, 61)) {
                    echo ", ";
                }
                // line 62
                echo "\t\t\t\t\t\t\t";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['tag']) ? $context['tag'] : null), "variants", array(), "any", false, 62));
                $context['loop'] = array(
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                );
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context['_key'] => $context['synonim']) {
                    // line 63
                    echo "\t\t\t\t\t\t\t\t<a href=\"/video/tag/";
                    echo twig_urlencode_filter((isset($context['synonim']) ? $context['synonim'] : null));
                    echo "\">
\t\t\t\t\t\t\t\t\t";
                    // line 64
                    echo (isset($context['synonim']) ? $context['synonim'] : null);
                    echo "
\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t";
                    // line 66
                    if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "last", array(), "any", false, 66))) {
                        echo ", ";
                    }
                    // line 67
                    echo "\t\t\t\t\t\t\t";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['length'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['synonim'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 68
                echo "\t\t\t\t\t\t";
            }
            echo "\t
\t\t\t\t\t\t&nbsp;
\t\t\t\t\t</span>\t
\t\t\t\t";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['tag'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 72
        echo "\t\t\t";
        if ($this->getAttribute((isset($context['item']) ? $context['item'] : null), "text", array(), "any", false, 72)) {
            // line 73
            echo "\t\t\t\t<span class=\"right\">
\t\t\t\t\t<a href=\"#\" class=\"show-description disabled\" rel=\"";
            // line 74
            echo (isset($context['id']) ? $context['id'] : null);
            echo "\">
\t\t\t\t\t\tПоказать описание
\t\t\t\t\t</a> 
\t\t\t\t\t<span class=\"arrow arrow-<?=\$item['id'];?>\" rel=\"off\">↓</span>
\t\t\t\t</span> 
\t\t\t";
        }
        // line 80
        echo "\t\t\t</p>
\t\t</div>
\t\t";
        // line 82
        if ($this->getAttribute((isset($context['item']) ? $context['item'] : null), "text", array(), "any", false, 82)) {
            // line 83
            echo "\t\t\t<div class=\"shell post description description-";
            echo (isset($context['id']) ? $context['id'] : null);
            echo " hidden\">
\t\t\t\t<h2>
\t\t\t\t\t<span class=\"postlink\">
\t\t\t\t\t\t<a href=\"#\" class=\"disabled\">
\t\t\t\t\t\t\tОписание
\t\t\t\t\t\t</a>
\t\t\t\t\t</span>
\t\t\t\t</h2>
\t\t\t\t<br />
\t\t\t\t<div class=\"posttext\">
\t\t\t\t\t";
            // line 93
            echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "text", array(), "any", false, 93);
            echo "
\t\t\t\t</div>
\t\t\t</div>
\t\t";
        }
        // line 97
        echo "\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "web/single/video.html";
    }
}
