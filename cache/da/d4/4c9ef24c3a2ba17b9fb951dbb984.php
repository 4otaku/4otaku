<?php

/* web/error.html */
class __TwigTemplate_dad44c9ef24c3a2ba17b9fb951dbb984 extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<a href=\"http://4otaku.ru\">Hello world!</a>
<br /><br />
";
        // line 3
        if ($this->getAttribute((isset($context['pic']) ? $context['pic'] : null), "resized", array(), "any", false, 3)) {
            // line 4
            echo "\t<img src=\"";
            echo twig_escape_filter($this->env, (isset($context['domain']) ? $context['domain'] : null), "html");
            echo "/images/art/resized/";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context['pic']) ? $context['pic'] : null), "md5", array(), "any", false, 4), "html");
            echo ".jpg\" />
";
        } else {
            // line 6
            echo "\t<img src=\"";
            echo twig_escape_filter($this->env, (isset($context['domain']) ? $context['domain'] : null), "html");
            echo "/images/art/full/";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context['pic']) ? $context['pic'] : null), "md5", array(), "any", false, 6), "html");
            echo ".";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context['pic']) ? $context['pic'] : null), "extension", array(), "any", false, 6), "html");
            echo "\" />
";
        }
    }

    public function getTemplateName()
    {
        return "web/error.html";
    }
}
