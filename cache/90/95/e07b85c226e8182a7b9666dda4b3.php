<?php

/* web/base.html */
class __TwigTemplate_9095e07b85c226e8182a7b9666dda4b3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'header' => array($this, 'block_header'),
            'content' => array($this, 'block_content'),
        );
    }

    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html lang=\"ru-RU\">
<head>
\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
\t<meta http-equiv=\"Pragma\" content=\"no-cache\" />
\t<meta http-equiv=\"Expires\" content=\"-1\" />
\t<title>";
        // line 7
        echo (isset($context['title']) ? $context['title'] : null);
        echo "</title>
\t<script type=\"text/javascript\" src=\"/jquery-1.5.1.min.js,config.js,box.js,main.js&ver=0\"></script>
\t<link rel=\"stylesheet\" href=\"/main.css&ver=0\" type=\"text/css\" media=\"screen\" />
</head>
<body>\t
\t<table width=\"100%\">
\t\t<tr>
\t\t\t";
        // line 14
        $this->displayBlock('header', $context, $blocks);
        // line 59
        echo "\t\t</tr>
\t\t<tr>
\t\t\t";
        // line 61
        $this->displayBlock('content', $context, $blocks);
        // line 62
        echo "\t\t</tr>
\t\t<tr>
\t\t\t<td colspan=\"2\" id=\"footer\">
\t\t\t\t<div class=\"left\">
\t\t\t\t\t2008-";
        // line 66
        echo twig_date_format_filter("now", "Y");
        echo " 4otaku.ru. <br />
\t\t\t\t\t<noindex>
\t\t\t\t\t\tE-mail для любых вопросов: <a href=\"mailto:admin@4otaku.ru\" target=\"_blank\">admin@4otaku.ru</a>.
\t\t\t\t\t</noindex>
\t\t\t\t</div>
\t\t\t\t<div class=\"right\">
\t\t\t\t\t<div>
\t\t\t\t\t\t<noindex>
\t\t\t\t\t\t\t<!--LiveInternet counter--><script type=\"text/javascript\">document.write(\"<a href=\\'http://www.liveinternet.ru/click\\' target=_blank><img src=\\'//counter.yadro.ru/hit?t14.14;r\" + escape(document.referrer) + ((typeof(screen)==\"undefined\")?\"\":\";s\"+screen.width+\"*\"+screen.height+\"*\"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + \";u\" + escape(document.URL) + \";\" + Math.random() + \"\\' border=0 width=88 height=31 alt=\\'\\' title=\\'LiveInternet: показано число просмотров за 24 часа, посетителей за 24 часа и за сегодня\\'><\\/a>\")</script><!--/LiveInternet-->
\t\t\t\t\t\t</noindex>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</td>
\t\t</tr>
\t</table>
</body>
</html>
";
    }

    // line 14
    public function block_header($context, array $blocks = array())
    {
        // line 15
        echo "\t\t\t\t<td colspan=\"2\" id=\"header\">
\t\t\t\t\t<table width=\"100%\">
\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t<div id=\"logo\">
\t\t\t\t\t\t\t\t\t<a href=\"/\"><img src=\"/i/4otakulogos.gif\" alt=\"4otaku\" /></a>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t
\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t<div id=\"top_buttons\">

\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t<div id=\"rss\" align=\"center\">
\t\t\t\t\t\t\t\t\t<div class=\"right\">\t\t
\t\t\t\t\t\t\t\t\t\t<a href=\"/go?http%3A%2F%2Ffeeds.feedburner.com%2F4otaku\" title=\"RSS записей\">
\t\t\t\t\t\t\t\t\t\t\t<img align=\"middle\" src=\"/i/feed_80x80.png\" alt=\"RSS записей\" />
\t\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t\t</div>\t\t
\t\t\t\t\t\t\t\t\t<div class=\"margin10 box first_box\">
\t\t\t\t\t\t\t\t\t\t<a href=\"/go?http%3A%2F%2Fwiki.4otaku.ru%2F%D0%9A%D0%B0%D1%82%D0%B5%D0%B3%D0%BE%D1%80%D0%B8%D1%8F:FAQ\" title=\"Частые вопросы по сайту\">
\t\t\t\t\t\t\t\t\t\t\tЧастые вопросы
\t\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t<div class=\"margin10 box\">
\t\t\t\t\t\t\t\t\t\t<a href=\"/ajax.php?m=box&f=rss&width=600&height=240\" title=\"Выберите, что показывать вам в RSS\" class=\"thickbox\">
\t\t\t\t\t\t\t\t\t\t\tВыберите свой RSS
\t\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t<div class=\"margin10 box\">
\t\t\t\t\t\t\t\t\t\t<a href=\"/ajax.php?m=box&f=settings&width=500&height=650\" title=\"Ваши личные настройки\" class=\"thickbox\">
\t\t\t\t\t\t\t\t\t\t\tНастройки
\t\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t</tr>
\t\t\t\t\t</table>
\t\t\t\t\t<div id=\"hline\"></div>
\t\t\t\t</td>
\t\t\t";
    }

    // line 61
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "web/base.html";
    }
}
