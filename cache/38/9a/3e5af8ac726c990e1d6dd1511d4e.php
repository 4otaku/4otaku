<?php

/* web/common/meta.html */
class __TwigTemplate_389a3e5af8ac726c990e1d6dd1511d4e extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "date", array(), "any", false, 1);
        echo " | 
";
        // line 2
        echo $this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta_header", array(), "any", false, 2), "author", array(), "any", false, 2);
        echo " 
";
        // line 3
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta", array(), "any", false, 3), "author", array(), "any", false, 3));
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
            // line 4
            echo "\t";
            if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 4))) {
                echo ", ";
            }
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t
\t<a href=\"";
            // line 5
            echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "base", array(), "any", false, 5);
            echo "author/";
            echo $this->getAttribute((isset($context['author']) ? $context['author'] : null), "alias", array(), "any", false, 5);
            echo "/\">
\t\t";
            // line 6
            echo $this->getAttribute((isset($context['author']) ? $context['author'] : null), "name", array(), "any", false, 6);
            echo "
\t</a>
";
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
        // line 8
        echo " | 
";
        // line 9
        echo $this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta_header", array(), "any", false, 9), "category", array(), "any", false, 9);
        echo " 
";
        // line 10
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta", array(), "any", false, 10), "category", array(), "any", false, 10));
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
            // line 11
            echo "\t";
            if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 11))) {
                echo ", ";
            }
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t
\t<a href=\"";
            // line 12
            echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "base", array(), "any", false, 12);
            echo "category/";
            echo $this->getAttribute((isset($context['category']) ? $context['category'] : null), "alias", array(), "any", false, 12);
            echo "/\">
\t\t";
            // line 13
            echo $this->getAttribute((isset($context['category']) ? $context['category'] : null), "name", array(), "any", false, 13);
            echo "
\t</a>
";
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
        // line 15
        echo " | 
";
        // line 16
        echo $this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta_header", array(), "any", false, 16), "language", array(), "any", false, 16);
        echo " 
";
        // line 17
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta", array(), "any", false, 17), "language", array(), "any", false, 17));
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
        foreach ($context['_seq'] as $context['key'] => $context['language']) {
            // line 18
            echo "\t";
            if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 18))) {
                echo ", ";
            }
            // line 19
            echo "\t<a href=\"";
            echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "base", array(), "any", false, 19);
            echo "language/";
            echo $this->getAttribute((isset($context['language']) ? $context['language'] : null), "alias", array(), "any", false, 19);
            echo "/\">
\t\t";
            // line 20
            echo $this->getAttribute((isset($context['language']) ? $context['language'] : null), "name", array(), "any", false, 20);
            echo "
\t</a>
";
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
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['language'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 22
        echo " | 
";
        // line 23
        echo $this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta_header", array(), "any", false, 23), "tag", array(), "any", false, 23);
        echo " 
";
        // line 24
        if ($this->getAttribute((isset($context['item']) ? $context['item'] : null), "have_tag_variants", array(), "any", false, 24)) {
            // line 25
            echo "\t<span class=\"synonims\">
\t\t<a href=\"#\" class=\"disabled\" title=\"Показать синонимы\" rel=\"";
            // line 26
            echo (isset($context['id']) ? $context['id'] : null);
            echo "\">
\t\t\t&gt;&gt;
\t\t</a>
\t</span> 
";
        }
        // line 31
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context['item']) ? $context['item'] : null), "meta", array(), "any", false, 31), "tag", array(), "any", false, 31));
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
            // line 32
            echo "\t<a href=\"";
            echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "base", array(), "any", false, 32);
            echo "tag/";
            echo $this->getAttribute((isset($context['tag']) ? $context['tag'] : null), "alias", array(), "any", false, 32);
            echo "/\">
\t\t";
            // line 33
            echo $this->getAttribute((isset($context['tag']) ? $context['tag'] : null), "name", array(), "any", false, 33);
            echo "
\t</a>
\t";
            // line 35
            if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "last", array(), "any", false, 35))) {
                echo ", ";
            }
            // line 36
            echo "\t<span class=\"hidden tag_synonims tag_synonims_";
            echo (isset($context['id']) ? $context['id'] : null);
            echo "\">
\t\t";
            // line 37
            if ($this->getAttribute((isset($context['tag']) ? $context['tag'] : null), "variants", array(), "any", false, 37)) {
                // line 38
                echo "\t\t\t";
                if ($this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "last", array(), "any", false, 38)) {
                    echo ", ";
                }
                // line 39
                echo "\t\t\t";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['tag']) ? $context['tag'] : null), "variants", array(), "any", false, 39));
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
                    // line 40
                    echo "\t\t\t\t<a href=\"";
                    echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "base", array(), "any", false, 40);
                    echo "tag/";
                    echo twig_urlencode_filter((isset($context['synonim']) ? $context['synonim'] : null));
                    echo "\">
\t\t\t\t\t";
                    // line 41
                    echo (isset($context['synonim']) ? $context['synonim'] : null);
                    echo "
\t\t\t\t</a>
\t\t\t\t";
                    // line 43
                    if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "last", array(), "any", false, 43))) {
                        echo ", ";
                    }
                    // line 44
                    echo "\t\t\t";
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
                // line 45
                echo "\t\t";
            }
            echo "\t
\t\t&nbsp;
\t</span>\t
";
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
    }

    public function getTemplateName()
    {
        return "web/common/meta.html";
    }
}
