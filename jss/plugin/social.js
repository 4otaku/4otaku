// ==UserScript==
// @name       vk.share
// @namespace  http://use.i.E.your.homepage/
// @version    0.2
// @description  enter something useful
// @match      http://4otaku.org/post/*
// @match      http://4otaku.org/video/*
// @match      http://4otaku.org/news/*
// @copyright  2013+, shtrih
// ==/UserScript==

function scriptBody() {
	var icon = 'https://vk.com/images/vk16.png',
		url = 'http://vk.com/share.php?url=',
		post_header = $('.display_item h2'),
		vk_button = $('<a/>', {
			text: 'Поделиться',
			target: '_blank',
			css: {
				padding:    '0 0 0 20px',
				margin:     '0 0 0 10px',
				background: 'url(' + icon + ') no-repeat 2px center',
				'font-size':  '0.7em'
			}
		})
		;

	post_header.each(function () {
		var post_link = $('a', this).attr('href'),
			share_url = url + 'http://4otaku.org' + encodeURI(post_link)
			;

		$(this).append(
			vk_button.clone().attr('href', share_url)
		);
	});
}

var script = document.createElement('script');
script.textContent = '(' + scriptBody.toString() + ')()';
(document.head||document.documentElement).appendChild(script);
script.parentNode.removeChild(script);