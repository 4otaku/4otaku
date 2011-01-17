<?php


$html = '
<h1>mPDF</h1>
<h2>Justification</h2>

<h4>Tables</h4>
<p>Text can be justified in table cells using in-line or stylesheet CSS. (Note that &lt;p&gt; tags are removed within cells along with any style definition or attributes.)</p>
<table class="bpmTopnTailC"><thead>
<tr class="headerrow"><th>Col/Row Header</th>
<td>
<p>Second column header p</p>
</td>
<td>Third column header</td>
</tr>
</thead><tbody>
<tr class="oddrow"><th>Row header 1</th>
<td>This is data</td>
<td>This is data</td>
</tr>
<tr class="evenrow"><th>Row header 2</th>
<td>
<p>This is data p</p>
</td>
<td>
<p>This is data</p>
</td>
</tr>
<tr class="oddrow"><th>
<p>Row header 3</p>
</th>
<td>
<p>This is long data</p>
</td>
<td>This is data</td>
</tr>
<tr class="evenrow"><th>
<p>Row header 4</p>
<p>&lt;th&gt; cell acting as header</p>
</th>
<td style="text-align:justify;"><p>Proin aliquet lorem id felis. Curabitur vel libero at mauris nonummy tincidunt. Donec imperdiet. Vestibulum sem sem, lacinia vel, molestie et, laoreet eget, urna. Curabitur viverra faucibus pede. Morbi lobortis. Donec dapibus. Donec tempus. Ut arcu enim, rhoncus ac, venenatis eu, porttitor mollis, dui. Sed vitae risus. In elementum sem placerat dui. Nam tristique eros in nisl. Nulla cursus sapien non quam porta porttitor. Quisque dictum ipsum ornare tortor. Fusce ornare tempus enim. </p></td>
<td>
<p>This is data</p>
</td>
</tr>
<tr class="oddrow"><th>Row header 5</th>
<td>Also data</td>
<td>Also data</td>
</tr>
<tr class="evenrow"><th>Row header 6</th>
<td>Also data</td>
<td>Also data</td>
</tr>
<tr class="oddrow"><th>Row header 7</th>
<td>Also data</td>
<td>Also data</td>
</tr>
<tr class="evenrow"><th>Row header 8</th>
<td>Also data</td>
<td>Also data</td>
</tr>
</tbody></table>
<p>&nbsp;</p>

<h4>Testing Justification with Long Words</h4>
<p>http://www-950.ibm.com/software/globalization/icu/demo/converters?s=ALL&amp;snd=4356&amp;dnd=4356</p>
<h5>Should not split</h5>
<p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend <i>verylongwordthatwontsplit</i> neque sit amet erat</p>
<p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend et <i>verylongwordthatwontsplit</i> neque sit amet erat</p>

<h5>Non-breaking Space &amp;nbsp;</h5><p>The next example has a non-breaking space between <i>eleifend</i> and the very long word.</p><p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend&nbsp;verylongwordthatwontsplitanywhere neque sit amet erat</p><p>Nbsp will only work in fonts that have a glyph to represent the character i.e. not in the CJK languages nor some Unicode fonts.</p>



<h4>Testing Justification with mixed Styles</h4>
<p>This is <s>strikethrough</s> in <b><s>block</s></b> and <small>small <s>strikethrough</s> in <i>small span</i></small> and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
<p>This is a <font color="#008800">green reference<sup>32-47</sup></font> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s> and then more text.
</p> 
<p><big>Repeated in <u>BIG</u>: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</big>
</p> 
<p><small>Repeated in small: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</small> 
</p>

<p style="font-size:7pt;">This is <s>strikethrough</s> in block and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>
<p style="font-size:7pt;">This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s> then more text.
</p> 
<p></p>
<p style="font-size:7pt;">
<big>Repeated in BIG: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</big>
</p>
';

//==============================================================
//==============================================================
// NB This only works using Core Fonts
// In other modes, you cannot set jSpacing at runtime, as it is 
// corrected automatically - unless you set useLang = false;
//==============================================================
include("../mpdf.php");

$mpdf=new mPDF('en-GB-c','A4','','',32,25,27,25,16,13); 

$mpdf->SetDisplayMode('fullpage');

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html);

// SPACING
$mpdf->WriteHTML("<h4>Spacing</h4><p>The PDF file definition will not allow word spacing to be changed in multibyte-encoded files. mPDF gets round this by changing the character spacing for each space. Thus both character- and word-spacing are possible. The default is a mixture of both. Only word spacing should be used for cursive languages such as Arabic, and character spacing is essential for CJK languages, where each character is a word.</p>");

$mpdf->jSpacing = 'C';

$mpdf->WriteHTML("<h5>Character spacing</h5><p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend <i>verylongwordthatwontsplitanywhere</i> neque sit amet erat</p>");

$mpdf->jSpacing = 'W';

$mpdf->WriteHTML("<h5>Word spacing</h5><p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend <i>verylongwordthatwontsplitanywhere</i> neque sit amet erat</p>");

$mpdf->jSpacing = '';

$mpdf->WriteHTML("<h5>Mixed Character and Word spacing</h5><p>Maecenas feugiat pede vel risus. Nulla et lectus eleifend <i>verylongwordthatwontsplitanywhere</i> neque sit amet erat</p>");


// ORPHANS
$mpdf->WriteHTML("<h4>Orphans</h4><p>Punctuation marks should not be split when the last word on a line is in &lt;tags&gt; eg <acronym>NATO</acronym>. The punctuation should go to the next line with the word if necessary.</p>");

$mpdf->WriteHTML("<p>Punctuation marks should not be split when the last word on a line is in &lt;tags&gt; e.g. <acronym>UNESCO</acronym>. The punctuation should go to the next line with the word if necessary.</p>");

$mpdf->WriteHTML("<p>Single marks .,?!;:\xe2\x80\x9e\xe2\x80\x9d should be preserved when last word on a line is in &lt;tags&gt; e.g. <acronym>NATO</acronym>? All of these are automatically protected in mPDF.</p>");


$mpdf->orphansAllowed = 0;

$mpdf->WriteHTML("<p>Similarly, sub and super texts should not be split when justifying text such as references<sup>23</sup>. (Note that this sentence has the orphansAllowed set to zero.) Altering the value of \$mpdf->orphansAllowed will determine how many extra characters can be preserved on a line; the next line allows 3 (default value 5):</p>");


$mpdf->orphansAllowed = 3;

$mpdf->WriteHTML("<p>Similarly, sub and super texts should not be split when justifying text such as references<sup>23</sup>. The references should go to the next line with the word if necessary.</p>");



$mpdf->Output();
exit;


?>