<?php

/* web/default.html */
class __TwigTemplate_4333d80037a33cb48dcb8ad53189b1f4 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    public function getParent(array $context)
    {
        if (null === $this->parent) {
            $this->parent = $this->env->loadTemplate("web/base.html");
        }

        return $this->parent;
    }

    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "\t<td valign=\"top\" id=\"content\">
\t\t<div class=\"post\">
\t\t\t";
        // line 6
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context['items']) ? $context['items'] : null));
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
        foreach ($context['_seq'] as $context['id'] => $context['item']) {
            // line 7
            echo "\t\t\t\t";
            $template = twig_join_filter(array(0 => "web/single/", 1 => $this->getAttribute((isset($context['item']) ? $context['item'] : null), "item_type", array(), "any", false, 7), 2 => ".html"));
            if (!$template instanceof Twig_Template) {
                $template = $this->env->loadTemplate($template);
            }
            $template->display($context);
            // line 8
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
        unset($context['_seq'], $context['_iterated'], $context['id'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        echo "\t\t\t
\t\t</div>
\t\t";
        // line 10
        if ((isset($context['navi']) ? $context['navi'] : null)) {
            // line 11
            echo "\t\t\t";
            $this->env->loadTemplate("web/common/navi.html")->display($context);
            // line 12
            echo "\t\t";
        }
        // line 13
        echo "\t</td>
\t<td valign=\"top\" id=\"sidebar\">
\t\t
\t</td>
";
    }

    public function getTemplateName()
    {
        return "web/default.html";
    }
}
