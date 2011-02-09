<?php

/* default/index.html */
class __TwigTemplate_5c468520f395fff909b6b1e713a7c42f extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "Hello world!
";
        // line 2
        echo twig_escape_filter($this->env, (isset($context['agent']) ? $context['agent'] : null), "html");
        echo "
";
    }

    public function getTemplateName()
    {
        return "default/index.html";
    }
}
