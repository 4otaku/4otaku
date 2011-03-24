<?php

/* web/navi.html */
class __TwigTemplate_7237c250069e1bfdd605234dee17f429 extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<div id=\"navi_bottom\">
\t<code>
\t\t<ul>
\t\t\t<li class=\"page_info\">
\t\t\t\tСтраница ";
        // line 5
        echo (isset($context['curr_page']) ? $context['curr_page'] : null);
        echo " из ";
        echo (isset($context['pagecount']) ? $context['pagecount'] : null);
        echo "
\t\t\t</li>
\t\t\t";
        // line 7
        if ((isset($context['navi_back']) ? $context['navi_back'] : null)) {
            // line 8
            echo "\t\t\t\t<li>
\t\t\t\t\t<a href=\"";
            // line 9
            echo (isset($context['navi_back']) ? $context['navi_back'] : null);
            echo "\">
\t\t\t\t\t\t&lt;
\t\t\t\t\t</a>
\t\t\t\t</li>
\t\t\t";
        }
        // line 14
        echo "\t\t\t";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context['navi']) ? $context['navi'] : null));
        foreach ($context['_seq'] as $context['number'] => $context['element']) {
            // line 15
            echo "\t\t\t\t";
            if (($this->getAttribute((isset($context['element']) ? $context['element'] : null), "type", array(), "any", false, 15) == "active")) {
                // line 16
                echo "\t\t\t\t\t<li class=\"active_page\">
\t\t\t\t\t\t<a href=\"";
                // line 17
                echo $this->getAttribute((isset($context['element']) ? $context['element'] : null), "url", array(), "any", false, 17);
                echo "\">
\t\t\t\t\t\t\t";
                // line 18
                echo (isset($context['number']) ? $context['number'] : null);
                echo "
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>
\t\t\t\t";
            } elseif (($this->getAttribute((isset($context['element']) ? $context['element'] : null), "type", array(), "any", false, 21) == "enabled")) {
                // line 22
                echo "\t\t\t\t\t<li>
\t\t\t\t\t\t<a href=\"";
                // line 23
                echo $this->getAttribute((isset($context['element']) ? $context['element'] : null), "url", array(), "any", false, 23);
                echo "\">
\t\t\t\t\t\t\t";
                // line 24
                echo (isset($context['number']) ? $context['number'] : null);
                echo "
\t\t\t\t\t\t</a>
\t\t\t\t\t</li>\t\t\t\t
\t\t\t\t";
            } else {
                // line 28
                echo "\t\t\t\t\t<li class=\"space\">
\t\t\t\t\t\t...
\t\t\t\t\t</li>
\t\t\t\t";
            }
            // line 32
            echo "\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['number'], $context['element'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 33
        echo "\t\t\t";
        if ((isset($context['navi_forward']) ? $context['navi_forward'] : null)) {
            // line 34
            echo "\t\t\t\t<li>
\t\t\t\t\t<a href=\"";
            // line 35
            echo (isset($context['navi_forward']) ? $context['navi_forward'] : null);
            echo "\">
\t\t\t\t\t\t&gt;
\t\t\t\t\t</a>
\t\t\t\t</li>
\t\t\t";
        }
        // line 40
        echo "\t\t</ul>
\t\t<div>
\t\t\t&nbsp;
\t\t</div>
\t</code>
</div>
";
    }

    public function getTemplateName()
    {
        return "web/navi.html";
    }
}
