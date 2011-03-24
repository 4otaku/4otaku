<?php

/* web/index.html */
class __TwigTemplate_8506667d82e17bee7f4a1738e84c9001 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'header' => array($this, 'block_header'),
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
    public function block_header($context, array $blocks = array())
    {
        // line 4
        echo "\t<td align=\"center\" class=\"index_topholder\">
\t\t<div class=\"center margin10\">
\t\t\t<a href=\"";
        // line 6
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/\">
\t\t\t\t<img src=\"/i/4otakulogo.png\" alt=\"4отаку. Материалы для отаку.\" style=\"margin-bottom: 15px;\">
\t\t\t</a>
\t\t</div>
\t\t<div class=\"center margin20\">
\t\t<a title=\"Dreams of Dead HQ\" href=\"http://dod.4otaku.ru\" class=\"with_help\">
\t\t\t<img src=\"/i/buttons/dod.png\">
\t\t</a>
\t\t<a title=\"Yukarin Subs\" href=\"http://yukarinsubs.4otaku.ru\" class=\"with_help\">
\t\t\t<img src=\"/i/buttons/ys.png\">
\t\t</a>
\t\t<a title=\"Архив\" href=\"";
        // line 17
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/archive/\" class=\"with_help\">
\t\t\t<img src=\"/i/buttons/arch.png\">
\t\t</a>
\t\t<a title=\"Кикаки: додзинси и ёнкомы\" href=\"http://raincat.4otaku.ru\" class=\"with_help\">
\t\t\t<img src=\"/i/buttons/ki.png\">
\t\t</a>
\t\t<a title=\"Частые вопросы по сайту\" href=\"http://wiki.4otaku.ru/%D0%9A%D0%B0%D1%82%D0%B5%D0%B3%D0%BE%D1%80%D0%B8%D1%8F:FAQ\" class=\"with_help\">
\t\t\t<img src=\"/i/buttons/faq.png\">
\t\t</a>
\t\t<a title=\"Что такое теория относительности?\" href=\"http://comics.4otaku.ru\" class=\"with_help\">
\t\t\t<img src=\"/i/buttons/chtto.png\">
\t\t</a>
\t\t</div>
\t\t<div class=\"center margin10\">
\t\t\t<input type=\"text\" size=\"50\" class=\"search\"> <input type=\"button\" value=\"искать\" class=\"searchb\">
\t\t</div>
\t\t<div id=\"search-tip\" class=\"center search-main\" rel=\"0\"></div>
\t\t<div class=\"center margin10\">
\t\t\t<input type=\"checkbox\" checked=\"checked\" value=\"p\" class=\"searcharea\"> В записях
\t\t\t<input type=\"checkbox\" checked=\"checked\" value=\"v\" class=\"searcharea\"> В видео
\t\t\t<input type=\"checkbox\" value=\"a\" class=\"searcharea\"> В артах
\t\t\t<input type=\"checkbox\" value=\"n\" class=\"searcharea\"> В новостях
\t\t\t<input type=\"checkbox\" checked=\"checked\" value=\"o\" class=\"searcharea\"> В столе заказов
\t\t\t<input type=\"checkbox\" value=\"c\" class=\"searcharea\"> В комментариях
\t\t</div>
\t\t<div class=\"yukari_corner\">
\t\t\t<div class=\"margin10\">
\t\t\t\t<a href=\"";
        // line 44
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/\">
\t\t\t\t\t<img src=\"/i/yukari.gif\" class=\"right\">
\t\t\t\t</a>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"rss_corner\">
\t\t\t<div class=\"right\">
\t\t\t\t<a href=\"/go?http%3A%2F%2Ffeeds.feedburner.com%2F4otaku\" title=\"RSS материалов\">
\t\t\t\t\t<img align=\"middle\" src=\"/i/feed_80x80.png\" alt=\"RSS материалов\" />
\t\t\t\t</a>
\t\t\t</div>
\t\t\t<div class=\"margin10 box first_index_box\">
\t\t\t\t<a href=\"";
        // line 56
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/ajax.php?m=box&f=rss&width=600&height=240\" title=\"Выберите, что показывать вам в RSS\" class=\"thickbox\">
\t\t\t\t\tВыберите свой RSS
\t\t\t\t</a>
\t\t\t</div>
\t\t\t<div class=\"margin10 box\">
\t\t\t\t<a href=\"";
        // line 61
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/ajax.php?m=box&f=settings&width=500&height=650\" title=\"Ваши личные настройки\" class=\"thickbox\">
\t\t\t\t\tНастройки
\t\t\t\t</a>
\t\t\t</div>
\t\t</div>
\t</td>
";
    }

    // line 69
    public function block_content($context, array $blocks = array())
    {
        // line 70
        echo "\t<td class=\"index_centerholder\" valign=\"top\">
\t\t<div class=\"mini-shell margin10\">
\t\t\tНаша комната в джаббере: main@room.4otaku.ru; 
\t\t\t<a href=\"http://jabberworld.info/%D0%9A%D0%BB%D0%B8%D0%B5%D0%BD%D1%82%D1%8B_Jabber\">
\t\t\t\tПомощь по настройке
\t\t\t</a>. 
\t\t\t<a href=\"";
        // line 76
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/logs/\">
\t\t\t\tЛоги
\t\t\t</a>. 
\t\t\t<span class=\"right\">
\t\t\t\t";
        // line 80
        echo (twig_test_defined("broken_links", $context) ? twig_default_filter((isset($context['broken_links']) ? $context['broken_links'] : null), 0) : 0);
        echo " битых ссылок. 
\t\t\t\t<a href=\"";
        // line 81
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/gouf/\">
\t\t\t\t\tПомочь
\t\t\t\t</a>.
\t\t\t</span>
\t\t</div>
\t\t<div class=\"mini-shell margin10\">
\t\t\t<a href=\"";
        // line 87
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/board/\">
\t\t\t\tБорда сайта
\t\t\t</a>. 
\t\t\tВсего тредов: ";
        // line 90
        echo ($this->getAttribute((isset($context['board']) ? $context['board'] : null), "all", array(), "any", true, 90) ? twig_default_filter($this->getAttribute((isset($context['board']) ? $context['board'] : null), "all", array(), "any", true, 90), 0) : 0);
        echo "
\t\t\t";
        // line 91
        if ($this->getAttribute((isset($context['board']) ? $context['board'] : null), "new", array(), "any", false, 91)) {
            // line 92
            echo "\t\t\t\t, <a href=\"";
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/board/new/";
            echo $this->getAttribute((isset($context['board']) ? $context['board'] : null), "link", array(), "any", false, 92);
            echo "\">
\t\t\t\t\t";
            // line 93
            echo $this->getAttribute((isset($context['board']) ? $context['board'] : null), "new", array(), "any", false, 93);
            echo " из них новых
\t\t\t\t</a>
\t\t\t";
        }
        // line 96
        echo "\t\t\t";
        if ($this->getAttribute((isset($context['board']) ? $context['board'] : null), "updated", array(), "any", false, 96)) {
            // line 97
            echo "\t\t\t\t, <a href=\"";
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/board/updated/";
            echo $this->getAttribute((isset($context['board']) ? $context['board'] : null), "link", array(), "any", false, 97);
            echo "\">
\t\t\t\t\t";
            // line 98
            echo $this->getAttribute((isset($context['board']) ? $context['board'] : null), "updated", array(), "any", false, 98);
            echo " обновилось
\t\t\t\t</a>
\t\t\t";
        }
        // line 101
        echo "\t\t\t.
\t\t\t<span class=\"right\">
\t\t\t\t";
        // line 103
        if ((isset($context['wiki']) ? $context['wiki'] : null)) {
            // line 104
            echo "\t\t\t\t\t<a href=\"http://wiki.4otaku.ru\">
\t\t\t\t\t\tВики сайта
\t\t\t\t\t</a>. 
\t\t\t\t\tПоследняя правка: 
\t\t\t\t\t<a href=\"http://wiki.4otaku.ru/";
            // line 108
            echo twig_urlencode_filter((isset($context['wiki']) ? $context['wiki'] : null));
            echo "\">
\t\t\t\t\t\t";
            // line 109
            echo (isset($context['wiki']) ? $context['wiki'] : null);
            echo "
\t\t\t\t\t</a>.
\t\t\t\t";
        }
        // line 112
        echo "\t\t\t</span>
\t\t</div>
\t\t";
        // line 114
        if ($this->getAttribute((isset($context['news']) ? $context['news'] : null), "title", array(), "any", false, 114)) {
            // line 115
            echo "\t\t\t<div class=\"compressed_news mini-shell clear ";
            if ((!$this->getAttribute((isset($context['news']) ? $context['news'] : null), "read", array(), "any", false, 115))) {
                echo "hidden";
            }
            echo " margin30\">
\t\t\t\t<a href=\"";
            // line 116
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/news/";
            echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "url", array(), "any", false, 116);
            echo "/\">
\t\t\t\t\t";
            // line 117
            echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "title", array(), "any", false, 117);
            echo "
\t\t\t\t</a>
\t\t\t\t";
            // line 119
            if ($this->getAttribute((isset($context['news']) ? $context['news'] : null), "comments", array(), "any", false, 119)) {
                echo " (";
                echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "comments", array(), "any", false, 119);
                echo ")";
            }
            // line 120
            echo "\t\t\t\t<a href=\"#\" class=\"uncompress_news togglenews news_bar\">
\t\t\t\t\tРазвернуть новость.
\t\t\t\t</a>
\t\t\t\t<a href=\"";
            // line 123
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/news/\" class=\"news_bar\">
\t\t\t\t\tАрхив новостей.
\t\t\t\t</a>
\t\t\t</div>
\t\t";
        }
        // line 128
        echo "\t\t<div class=\"left index_smallcolumn defaultvideoholder\">
\t\t\t<div class=\"mainblock\">
\t\t\t\t<p class=\"head\">
\t\t\t\t\t<a href=\"";
        // line 131
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/post/\">
\t\t\t\t\t\tЗаписи
\t\t\t\t\t</a>
\t\t\t\t</p>
\t\t\t\tВсего ";
        // line 135
        echo ($this->getAttribute((isset($context['post']) ? $context['post'] : null), "total", array(), "any", true, 135) ? twig_default_filter($this->getAttribute((isset($context['post']) ? $context['post'] : null), "total", array(), "any", true, 135), 0) : 0);
        echo " записей. 
\t\t\t\t";
        // line 136
        if ($this->getAttribute((isset($context['post']) ? $context['post'] : null), "new", array(), "any", false, 136)) {
            echo $this->getAttribute((isset($context['post']) ? $context['post'] : null), "new", array(), "any", false, 136);
            echo " из них новых";
        }
        // line 137
        echo "\t\t\t\t";
        if ($this->getAttribute((isset($context['post']) ? $context['post'] : null), "latest", array(), "any", false, 137)) {
            // line 138
            echo "\t\t\t\t\tПоследние записи: <br /><br />
\t\t\t\t\t";
            // line 139
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['post']) ? $context['post'] : null), "latest", array(), "any", false, 139));
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
            foreach ($context['_seq'] as $context['key'] => $context['one']) {
                // line 140
                echo "\t\t\t\t\t\t";
                if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 140))) {
                    echo "<br />";
                }
                // line 141
                echo "\t\t\t\t\t\t<a href=\"";
                echo (isset($context['domain']) ? $context['domain'] : null);
                echo "/post/";
                echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "id", array(), "any", false, 141);
                echo "\" class=\"with_help\" title=\"";
                echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "headline", array(), "any", false, 141);
                echo "\">
\t\t\t\t\t\t\t";
                // line 142
                echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "title", array(), "any", false, 142);
                echo "
\t\t\t\t\t\t</a>
\t\t\t\t\t\t";
                // line 144
                if ($this->getAttribute((isset($context['one']) ? $context['one'] : null), "comments", array(), "any", false, 144)) {
                    echo " (";
                    echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "comments", array(), "any", false, 144);
                    echo ")";
                }
                // line 145
                echo "\t\t\t\t\t";
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
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['one'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 146
            echo "\t\t\t\t";
        }
        // line 147
        echo "\t\t\t</div>
\t\t\t";
        // line 148
        if ((!$this->getAttribute((isset($context['news']) ? $context['news'] : null), "read", array(), "any", false, 148))) {
            // line 149
            echo "\t\t\t\t<div class=\"mainblock videoblock\">
\t\t\t\t\t<p class=\"head\">
\t\t\t\t\t\t<a href=\"";
            // line 151
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/video/\">
\t\t\t\t\t\t\tВидео
\t\t\t\t\t\t</a>
\t\t\t\t\t</p>
\t\t\t\t\tВсего ";
            // line 155
            echo ($this->getAttribute((isset($context['video']) ? $context['video'] : null), "total", array(), "any", true, 155) ? twig_default_filter($this->getAttribute((isset($context['video']) ? $context['video'] : null), "total", array(), "any", true, 155), 0) : 0);
            echo " видео. 
\t\t\t\t\t";
            // line 156
            if ($this->getAttribute((isset($context['video']) ? $context['video'] : null), "new", array(), "any", false, 156)) {
                echo $this->getAttribute((isset($context['video']) ? $context['video'] : null), "new", array(), "any", false, 156);
                echo " из них новых";
            }
            // line 157
            echo "\t\t\t\t\t";
            if ($this->getAttribute((isset($context['video']) ? $context['video'] : null), "latest", array(), "any", false, 157)) {
                // line 158
                echo "\t\t\t\t\t\tПоследние записи: <br /><br />
\t\t\t\t\t\t";
                // line 159
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['video']) ? $context['video'] : null), "latest", array(), "any", false, 159));
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
                foreach ($context['_seq'] as $context['key'] => $context['one']) {
                    // line 160
                    echo "\t\t\t\t\t\t\t";
                    if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 160))) {
                        echo "<br />";
                    }
                    // line 161
                    echo "\t\t\t\t\t\t\t<a href=\"";
                    echo (isset($context['domain']) ? $context['domain'] : null);
                    echo "/video/";
                    echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "id", array(), "any", false, 161);
                    echo "\" class=\"with_help\" title=\"";
                    echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "headline", array(), "any", false, 161);
                    echo "\">
\t\t\t\t\t\t\t\t";
                    // line 162
                    echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "title", array(), "any", false, 162);
                    echo "
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t";
                    // line 164
                    if ($this->getAttribute((isset($context['one']) ? $context['one'] : null), "comments", array(), "any", false, 164)) {
                        echo " (";
                        echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "comments", array(), "any", false, 164);
                        echo ")";
                    }
                    // line 165
                    echo "\t\t\t\t\t\t";
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
                unset($context['_seq'], $context['_iterated'], $context['key'], $context['one'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 166
                echo "\t\t\t\t\t";
            }
            // line 167
            echo "\t\t\t\t</div>
\t\t\t";
        }
        // line 169
        echo "\t\t</div>
\t\t<div class=\"left index_smallcolumn ";
        // line 170
        if ($this->getAttribute((isset($context['news']) ? $context['news'] : null), "read", array(), "any", false, 170)) {
            echo "hidden";
        }
        echo " videoholder\">
\t\t\t";
        // line 171
        if ($this->getAttribute((isset($context['news']) ? $context['news'] : null), "read", array(), "any", false, 171)) {
            // line 172
            echo "\t\t\t\t<div class=\"mainblock videoblock\">
\t\t\t\t\t<p class=\"head\">
\t\t\t\t\t\t<a href=\"";
            // line 174
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/video/\">
\t\t\t\t\t\t\tВидео
\t\t\t\t\t\t</a>
\t\t\t\t\t</p>
\t\t\t\t\tВсего ";
            // line 178
            echo ($this->getAttribute((isset($context['video']) ? $context['video'] : null), "total", array(), "any", true, 178) ? twig_default_filter($this->getAttribute((isset($context['video']) ? $context['video'] : null), "total", array(), "any", true, 178), 0) : 0);
            echo " видео. 
\t\t\t\t\t";
            // line 179
            if ($this->getAttribute((isset($context['video']) ? $context['video'] : null), "new", array(), "any", false, 179)) {
                echo $this->getAttribute((isset($context['video']) ? $context['video'] : null), "new", array(), "any", false, 179);
                echo " из них новых";
            }
            // line 180
            echo "\t\t\t\t\t";
            if ($this->getAttribute((isset($context['video']) ? $context['video'] : null), "latest", array(), "any", false, 180)) {
                // line 181
                echo "\t\t\t\t\t\tПоследние записи: <br /><br />
\t\t\t\t\t\t";
                // line 182
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['video']) ? $context['video'] : null), "latest", array(), "any", false, 182));
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
                foreach ($context['_seq'] as $context['key'] => $context['one']) {
                    // line 183
                    echo "\t\t\t\t\t\t\t";
                    if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 183))) {
                        echo "<br />";
                    }
                    // line 184
                    echo "\t\t\t\t\t\t\t<a href=\"";
                    echo (isset($context['domain']) ? $context['domain'] : null);
                    echo "/video/";
                    echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "id", array(), "any", false, 184);
                    echo "\" class=\"with_help\" title=\"";
                    echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "headline", array(), "any", false, 184);
                    echo "\">
\t\t\t\t\t\t\t\t";
                    // line 185
                    echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "title", array(), "any", false, 185);
                    echo "
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t";
                    // line 187
                    if ($this->getAttribute((isset($context['one']) ? $context['one'] : null), "comments", array(), "any", false, 187)) {
                        echo " (";
                        echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "comments", array(), "any", false, 187);
                        echo ")";
                    }
                    // line 188
                    echo "\t\t\t\t\t\t";
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
                unset($context['_seq'], $context['_iterated'], $context['key'], $context['one'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 189
                echo "\t\t\t\t\t";
            }
            // line 190
            echo "\t\t\t\t</div>
\t\t\t";
        }
        // line 192
        echo "\t\t</div>
\t\t<div class=\"left index_smallcolumn ";
        // line 193
        if ((!$this->getAttribute((isset($context['news']) ? $context['news'] : null), "read", array(), "any", false, 193))) {
            echo "hidden";
        }
        echo " artholder\">
\t\t\t";
        // line 194
        if ($this->getAttribute((isset($context['news']) ? $context['news'] : null), "read", array(), "any", false, 194)) {
            // line 195
            echo "\t\t\t\t<div class=\"mainblock artblock\">
\t\t\t\t\t<p class=\"head\">
\t\t\t\t\t\t<a href=\"";
            // line 197
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/art/\">
\t\t\t\t\t\t\tАрт
\t\t\t\t\t\t</a>
\t\t\t\t\t</p>
\t\t\t\t\tВсего ";
            // line 201
            echo ($this->getAttribute((isset($context['art']) ? $context['art'] : null), "total", array(), "any", true, 201) ? twig_default_filter($this->getAttribute((isset($context['art']) ? $context['art'] : null), "total", array(), "any", true, 201), 0) : 0);
            echo " артов. 
\t\t\t\t\t";
            // line 202
            if ($this->getAttribute((isset($context['art']) ? $context['art'] : null), "new", array(), "any", false, 202)) {
                echo $this->getAttribute((isset($context['art']) ? $context['art'] : null), "new", array(), "any", false, 202);
                echo " из них новых";
            }
            // line 203
            echo "\t\t\t\t\t";
            if ($this->getAttribute($this->getAttribute((isset($context['art']) ? $context['art'] : null), "latest", array(), "any", false, 203), "thumb", array(), "any", false, 203)) {
                // line 204
                echo "\t\t\t\t\t\tПоследнее изображение: <br /><br />
\t\t\t\t\t\t<div style=\"text-align:center; width: 100%;\">
\t\t\t\t\t\t\t<a href=\"";
                // line 206
                echo (isset($context['domain']) ? $context['domain'] : null);
                echo "/art/";
                echo $this->getAttribute($this->getAttribute((isset($context['art']) ? $context['art'] : null), "latest", array(), "any", false, 206), "id", array(), "any", false, 206);
                echo "\">
\t\t\t\t\t\t\t\t<img src=\"";
                // line 207
                echo (isset($context['domain']) ? $context['domain'] : null);
                echo "/images/art/thumbnail/";
                echo $this->getAttribute($this->getAttribute((isset($context['art']) ? $context['art'] : null), "latest", array(), "any", false, 207), "thumb", array(), "any", false, 207);
                echo ".jpg\">
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t";
            }
            // line 211
            echo "\t\t\t\t</div>
\t\t\t";
        }
        // line 213
        echo "\t\t</div>
\t\t<div class=\"left index_smallcolumn defaultartholder\">
\t\t\t<div class=\"mainblock\">
\t\t\t\t<p class=\"head\">
\t\t\t\t\t<a href=\"";
        // line 217
        echo (isset($context['domain']) ? $context['domain'] : null);
        echo "/order/\">
\t\t\t\t\t\tЗаказы
\t\t\t\t\t</a>
\t\t\t\t</p>
\t\t\t\tВсего ";
        // line 221
        echo ($this->getAttribute((isset($context['order']) ? $context['order'] : null), "total", array(), "any", true, 221) ? twig_default_filter($this->getAttribute((isset($context['order']) ? $context['order'] : null), "total", array(), "any", true, 221), 0) : 0);
        echo " заказов. 
\t\t\t\t";
        // line 222
        if ($this->getAttribute((isset($context['order']) ? $context['order'] : null), "new", array(), "any", false, 222)) {
            echo $this->getAttribute((isset($context['order']) ? $context['order'] : null), "new", array(), "any", false, 222);
            echo " из них открыты";
        }
        // line 223
        echo "\t\t\t\t";
        if ($this->getAttribute((isset($context['order']) ? $context['order'] : null), "latest", array(), "any", false, 223)) {
            // line 224
            echo "\t\t\t\t\tПоследние заказы: <br /><br />
\t\t\t\t\t";
            // line 225
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context['order']) ? $context['order'] : null), "latest", array(), "any", false, 225));
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
            foreach ($context['_seq'] as $context['key'] => $context['one']) {
                // line 226
                echo "\t\t\t\t\t\t";
                if ((!$this->getAttribute((isset($context['loop']) ? $context['loop'] : null), "first", array(), "any", false, 226))) {
                    echo "<br /><br />";
                }
                // line 227
                echo "\t\t\t\t\t\t";
                echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "username", array(), "any", false, 227);
                echo " заказал 
\t\t\t\t\t\t<a href=\"";
                // line 228
                echo (isset($context['domain']) ? $context['domain'] : null);
                echo "/video/";
                echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "id", array(), "any", false, 228);
                echo "\" class=\"with_help\" title=\"";
                echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "headline", array(), "any", false, 228);
                echo "\">
\t\t\t\t\t\t\t";
                // line 229
                echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "title", array(), "any", false, 229);
                echo "
\t\t\t\t\t\t</a>
\t\t\t\t\t\t";
                // line 231
                if ($this->getAttribute((isset($context['one']) ? $context['one'] : null), "comments", array(), "any", false, 231)) {
                    echo " (";
                    echo $this->getAttribute((isset($context['one']) ? $context['one'] : null), "comments", array(), "any", false, 231);
                    echo ")";
                }
                // line 232
                echo "\t\t\t\t\t";
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
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['one'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 233
            echo "\t\t\t\t";
        }
        // line 234
        echo "\t\t\t</div>
\t\t\t";
        // line 235
        if ((!$this->getAttribute((isset($context['news']) ? $context['news'] : null), "read", array(), "any", false, 235))) {
            // line 236
            echo "\t\t\t\t<div class=\"mainblock artblock\">
\t\t\t\t\t<p class=\"head\">
\t\t\t\t\t\t<a href=\"";
            // line 238
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/art/\">
\t\t\t\t\t\t\tАрт
\t\t\t\t\t\t</a>
\t\t\t\t\t</p>
\t\t\t\t\tВсего ";
            // line 242
            echo ($this->getAttribute((isset($context['art']) ? $context['art'] : null), "total", array(), "any", true, 242) ? twig_default_filter($this->getAttribute((isset($context['art']) ? $context['art'] : null), "total", array(), "any", true, 242), 0) : 0);
            echo " артов. 
\t\t\t\t\t";
            // line 243
            if ($this->getAttribute((isset($context['art']) ? $context['art'] : null), "new", array(), "any", false, 243)) {
                echo $this->getAttribute((isset($context['art']) ? $context['art'] : null), "new", array(), "any", false, 243);
                echo " из них новых";
            }
            // line 244
            echo "\t\t\t\t\t";
            if ($this->getAttribute($this->getAttribute((isset($context['art']) ? $context['art'] : null), "latest", array(), "any", false, 244), "thumbnail", array(), "any", false, 244)) {
                // line 245
                echo "\t\t\t\t\t\tПоследнее изображение: <br /><br />
\t\t\t\t\t\t<div style=\"text-align:center; width: 100%;\">
\t\t\t\t\t\t\t<a href=\"";
                // line 247
                echo (isset($context['domain']) ? $context['domain'] : null);
                echo "/art/";
                echo $this->getAttribute($this->getAttribute((isset($context['art']) ? $context['art'] : null), "latest", array(), "any", false, 247), "id", array(), "any", false, 247);
                echo "\">
\t\t\t\t\t\t\t\t<img src=\"";
                // line 248
                echo (isset($context['domain']) ? $context['domain'] : null);
                echo "/images/art/thumbnail/";
                echo $this->getAttribute($this->getAttribute((isset($context['art']) ? $context['art'] : null), "latest", array(), "any", false, 248), "thumbnail", array(), "any", false, 248);
                echo ".jpg\">
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t";
            }
            // line 252
            echo "\t\t\t\t</div>
\t\t\t";
        }
        // line 254
        echo "\t\t</div>
\t\t";
        // line 255
        if ($this->getAttribute((isset($context['news']) ? $context['news'] : null), "title", array(), "any", false, 255)) {
            // line 256
            echo "\t\t\t<div class=\"left ";
            if ($this->getAttribute((isset($context['news']) ? $context['news'] : null), "read", array(), "any", false, 256)) {
                echo "hidden";
            }
            echo " index_largecolumn\">
\t\t\t\t<div class=\"post mainblock\">
\t\t\t\t\t<p class=\"head\">
\t\t\t\t\t\t<a href=\"";
            // line 259
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/news/";
            echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "url", array(), "any", false, 259);
            echo "/\">
\t\t\t\t\t\t\t";
            // line 260
            echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "title", array(), "any", false, 260);
            echo "
\t\t\t\t\t\t</a>
\t\t\t\t\t</p>
\t\t\t\t\t<div class=\"entry\">
\t\t\t\t\t\t<a href=\"";
            // line 264
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/images/news/full/";
            echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "image", array(), "any", false, 264);
            echo "\" target=\"_blank\">
\t\t\t\t\t\t\t<img src=\"";
            // line 265
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/images/news/thumbnail/";
            echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "image", array(), "any", false, 265);
            echo "\" align=\"left\" class=\"news_image\" />
\t\t\t\t\t\t</a>
\t\t\t\t\t\t";
            // line 267
            echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "text", array(), "any", false, 267);
            echo "
\t\t\t\t\t</div>
\t\t\t\t\t<span class=\"semi_large\">
\t\t\t\t\t\t<a href=\"";
            // line 270
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/news/";
            echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "url", array(), "any", false, 270);
            echo "/\">
\t\t\t\t\t\t\tКомментировать
\t\t\t\t\t\t</a>.
\t\t\t\t\t\t";
            // line 273
            if ($this->getAttribute((isset($context['news']) ? $context['news'] : null), "comments", array(), "any", false, 273)) {
                echo " (";
                echo $this->getAttribute((isset($context['news']) ? $context['news'] : null), "comments", array(), "any", false, 273);
                echo ")";
            }
            // line 274
            echo "\t\t\t\t\t\t<a href=\"#\" class=\"right compress_news togglenews\">
\t\t\t\t\t\t\tЯ прочел, уберите новость.
\t\t\t\t\t\t</a>
\t\t\t\t\t</span>
\t\t\t\t\t<div class=\"center clear\">
\t\t\t\t\t\t<a href=\"";
            // line 279
            echo (isset($context['domain']) ? $context['domain'] : null);
            echo "/news/\">
\t\t\t\t\t\t\tАрхив новостей.
\t\t\t\t\t\t</a>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t";
        }
        // line 286
        echo "\t</td>
";
    }

    public function getTemplateName()
    {
        return "web/index.html";
    }
}
