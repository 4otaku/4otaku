<?php

/* web/single/post.html */
class __TwigTemplate_bf2199e331d751202b0fe312886d9ec8 extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<div class=\"shell\">
\t<div class=\"post\" id=\"post-";
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
\t\t\t<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
\t\t\t\t<tr>
\t\t\t\t\t";
        // line 25
        if ($this->getAttribute((isset($context['item']) ? $context['item'] : null), "image", array(), "any", false, 25)) {
            // line 26
            echo "\t\t\t\t\t\t<td class=\"imageholder\">
\t\t\t\t\t\t\t";
            // line 27
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['item']) ? $context['item'] : null), "image", array(), "any", false, 27));
            foreach ($context['_seq'] as $context['key'] => $context['image']) {
                // line 28
                echo "\t\t\t\t\t\t\t\t<div class=\"image-";
                echo (isset($context['key']) ? $context['key'] : null);
                echo "\">
\t\t\t\t\t\t\t\t\t<a href=\"";
                // line 29
                echo (isset($context['domain']) ? $context['domain'] : null);
                echo "/images/post/full/";
                echo $this->getAttribute((isset($context['image']) ? $context['image'] : null), "file", array(), "any", false, 29);
                echo "\" target=\"_blank\">
\t\t\t\t\t\t\t\t\t\t<img src=\"";
                // line 30
                echo (isset($context['domain']) ? $context['domain'] : null);
                echo "/images/post/thumbnail/";
                echo $this->getAttribute((isset($context['image']) ? $context['image'] : null), "file", array(), "any", false, 30);
                echo "\" /> 
\t\t\t\t\t\t\t\t\t</a>\t\t\t\t\t\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t</div>\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['image'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 34
            echo "\t\t\t\t\t\t</td>
\t\t\t\t\t";
        }
        // line 36
        echo "\t\t\t\t\t<td valign=\"top\">
\t\t\t\t\t\t<div class=\"posttext\">
\t\t\t\t\t\t\t";
        // line 38
        echo $this->getAttribute((isset($context['item']) ? $context['item'] : null), "text", array(), "any", false, 38);
        echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t\t";
        // line 40
        if ($this->getAttribute((isset($context['item']) ? $context['item'] : null), "link", array(), "any", false, 40)) {
            // line 41
            echo "\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t";
            // line 42
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['item']) ? $context['item'] : null), "link", array(), "any", false, 42));
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
            foreach ($context['_seq'] as $context['key'] => $context['link']) {
                // line 43
                echo "\t\t\t\t\t\t\t\t\t";
                if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 43))) {
                    echo "<br />";
                }
                // line 44
                echo "\t\t\t\t\t\t\t\t\t";
                echo $this->getAttribute((isset($context['link']) ? $context['link'] : null), "name", array(), "any", false, 44);
                echo ": 
\t\t\t\t\t\t\t\t\t";
                // line 45
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['link']) ? $context['link'] : null), "url", array(), "any", false, 45));
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
                foreach ($context['_seq'] as $context['url'] => $context['alias']) {
                    // line 46
                    echo "\t\t\t\t\t\t\t\t\t\t";
                    if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 46))) {
                        echo "|";
                    }
                    echo " 
\t\t\t\t\t\t\t\t\t\t<a href=\"";
                    // line 47
                    echo (isset($context['url']) ? $context['url'] : null);
                    echo "\" target=\"_blank\">
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 48
                    echo (isset($context['alias']) ? $context['alias'] : null);
                    echo "
\t\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t\t";
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
                unset($context['_seq'], $context['_iterated'], $context['url'], $context['alias'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 51
                echo "\t\t\t\t\t\t\t\t\t (~";
                echo $this->getAttribute((isset($context['link']) ? $context['link'] : null), "size", array(), "any", false, 51);
                echo " ";
                echo $this->getAttribute((isset($context['link']) ? $context['link'] : null), "sizetype", array(), "any", false, 51);
                echo ")\t\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t";
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
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['link'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 53
            echo "\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t";
        }
        // line 54
        echo "\t
\t\t\t\t\t\t";
        // line 55
        if ($this->getAttribute((isset($context['item']) ? $context['item'] : null), "file", array(), "any", false, 55)) {
            // line 56
            echo "\t\t\t\t\t\t\t<p class=\"post-files\">
\t\t\t\t\t\t\t\t";
            // line 57
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['item']) ? $context['item'] : null), "file", array(), "any", false, 57));
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
            foreach ($context['_seq'] as $context['key'] => $context['file']) {
                // line 58
                echo "\t\t\t\t\t\t\t\t\t";
                if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 58))) {
                    echo "<br />";
                }
                // line 59
                echo "\t\t\t\t\t\t\t\t\t";
                if (($this->getAttribute((isset($context['file']) ? $context['file'] : null), "type", array(), "any", false, 59) == "plain")) {
                    // line 60
                    echo "\t\t\t\t\t\t\t\t\t\t<img src=\"/i/file.png\" class=\"post-image\"> 
\t\t\t\t\t\t\t\t\t\t";
                    // line 61
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "name", array(), "any", false, 61);
                    echo ": 
\t\t\t\t\t\t\t\t\t\t<a href=\"";
                    // line 62
                    echo (isset($context['domain']) ? $context['domain'] : null);
                    echo "/files/post/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "type", array(), "any", false, 62);
                    echo "/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "folder", array(), "any", false, 62);
                    echo "/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "filename", array(), "any", false, 62);
                    echo "\">
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 63
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "filename", array(), "any", false, 63);
                    echo "
\t\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t\t\t (";
                    // line 65
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "size", array(), "any", false, 65);
                    echo ")\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\t";
                } elseif (($this->getAttribute((isset($context['file']) ? $context['file'] : null), "type", array(), "any", false, 66) == "image")) {
                    // line 66
                    echo "\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\t\t<img src=\"/i/file-image.png\" class=\"post-image\"> 
\t\t\t\t\t\t\t\t\t\t";
                    // line 68
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "name", array(), "any", false, 68);
                    echo ": 
\t\t\t\t\t\t\t\t\t\t<a href=\"";
                    // line 69
                    echo (isset($context['domain']) ? $context['domain'] : null);
                    echo "/files/post/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "type", array(), "any", false, 69);
                    echo "/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "folder", array(), "any", false, 69);
                    echo "/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "filename", array(), "any", false, 69);
                    echo "\" class=\"imageholder\" target=\"_blank\">
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 70
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "filename", array(), "any", false, 70);
                    echo "
\t\t\t\t\t\t\t\t\t\t\t<span rel=\"";
                    // line 71
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "height", array(), "any", false, 71);
                    echo "\">
\t\t\t\t\t\t\t\t\t\t\t\t<img class=\"hiddenthumb\" src=\"#\" rel=\"";
                    // line 72
                    echo (isset($context['domain']) ? $context['domain'] : null);
                    echo "/files/post/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "type", array(), "any", false, 72);
                    echo "/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "folder", array(), "any", false, 72);
                    echo "/thumb_";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "filename", array(), "any", false, 72);
                    echo "\" />
\t\t\t\t\t\t\t\t\t\t\t</span>\t
\t\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t\t\t (";
                    // line 75
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "size", array(), "any", false, 75);
                    echo ")\t\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\t";
                } elseif (($this->getAttribute((isset($context['file']) ? $context['file'] : null), "type", array(), "any", false, 76) == "audio")) {
                    // line 77
                    echo "\t\t\t\t\t\t\t\t\t\t<img src=\"/i/file-audio.png\" class=\"post-image\"> 
\t\t\t\t\t\t\t\t\t\t<span>";
                    // line 78
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "name", array(), "any", false, 78);
                    echo ": </span>
\t\t\t\t\t\t\t\t\t\t<object type=\"application/x-shockwave-flash\" align=\"bottom\" data=\"/templates/musicplayer/player_mp3_maxi.swf\" width=\"250\" height=\"16\">
\t\t\t\t\t\t\t\t\t\t\t<param name=\"movie\" value=\"/templates/musicplayer/player_mp3_maxi.swf\" />
\t\t\t\t\t\t\t\t\t\t\t<param name=\"bgcolor\" value=\"#ffffff\" />
\t\t\t\t\t\t\t\t\t\t\t<param name=\"FlashVars\" value=\"mp3=";
                    // line 82
                    echo twig_urlencode_filter((isset($context['domain']) ? $context['domain'] : null));
                    echo "/files/post/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "type", array(), "any", false, 82);
                    echo "/";
                    echo $this->getAttribute((isset($context['file']) ? $context['file'] : null), "folder", array(), "any", false, 82);
                    echo "/";
                    echo twig_urlencode_filter($this->getAttribute((isset($context['file']) ? $context['file'] : null), "filename", array(), "any", false, 82));
                    echo "&amp;width=250&amp;height=16&amp;showstop=1&amp;showvolume=1&amp;buttonwidth=20&amp;sliderwidth=15&amp;volumewidth=40\" />
\t\t\t\t\t\t\t\t\t\t</object>
\t\t\t\t\t\t\t\t\t";
                }
                // line 84
                echo "\t\t\t\t\t
\t\t\t\t\t\t\t\t";
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
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['file'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 86
            echo "\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t";
        }
        // line 88
        echo "\t\t\t\t\t\t";
        if ($this->getAttribute((isset($context['item']) ? $context['item'] : null), "info", array(), "any", false, 88)) {
            // line 89
            echo "\t\t\t\t\t\t\t<p>
\t\t\t\t\t\t\t\t";
            // line 90
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['item']) ? $context['item'] : null), "info", array(), "any", false, 90));
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
            foreach ($context['_seq'] as $context['key'] => $context['info']) {
                // line 91
                echo "\t\t\t\t\t\t\t\t\t";
                if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 91))) {
                    echo "<br />";
                }
                // line 92
                echo "\t\t\t\t\t\t\t\t\t";
                echo $this->getAttribute((isset($context['info']) ? $context['info'] : null), "name", array(), "any", false, 92);
                echo ": 
\t\t\t\t\t\t\t\t\t<a href=\"";
                // line 93
                echo $this->getAttribute((isset($context['info']) ? $context['info'] : null), "url", array(), "any", false, 93);
                echo "\" target=\"_blank\">
\t\t\t\t\t\t\t\t\t\t";
                // line 94
                echo $this->getAttribute((isset($context['info']) ? $context['info'] : null), "alias", array(), "any", false, 94);
                echo "
\t\t\t\t\t\t\t\t\t</a>\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t";
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
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['info'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 97
            echo "\t\t\t\t\t\t\t</p>
\t\t\t\t\t\t";
        }
        // line 98
        echo "\t\t
\t\t\t\t\t</td>
\t\t\t\t</tr>
\t\t\t</table>
\t\t</div>
\t\t<div class=\"wrapper\">
\t\t\t<p class=\"meta\">
\t\t\t\t";
        // line 105
        $this->env->loadTemplate("web/common/meta.html")->display($context);
        // line 106
        echo "\t\t\t</p>
\t\t</div>
\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "web/single/post.html";
    }
}
