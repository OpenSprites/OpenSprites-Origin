<?php
// no bad words in source code, unlike LLK/s2forums :P
// Thanks to LLK for this filter
$bad_word_detector = str_rot13("/(?v)\\o(shtyl|(\\j*?)shpx(\\j*?)|s(h|i|\\*)?p?x(vat?)?|(\\j*?)fu(v|1|y)g(\\j*?)|pe(n|@|\\*)c(cre|crq|l)?|(onq|qhzo|wnpx)?(n|@)ff(u(b|0)yr|jvcr)?|(onq|qhzo|wnpx)?(n|@)efr(u(b|0)yr|jvcr)?|onfgneq|o(v|1|y|\\*)?g?pu(r?f)?|phag|phz|(tbq?)?qnz(a|z)(vg)?|qbhpur(\\j*?)|(arj)?snt(tbg|tng)?|sevt(tra|tva|tvat)?|bzst|cvff(\\j*?)|cbea|encr|ergneq|frk|f r k|fung|fyhg|gvg|ju(b|0)er(\\j*?)|jg(s|su|u))(f|rq)?\\o/v");
function hasBadWords($text){
	global $bad_word_detector;
	return preg_match($bad_word_detector, $text) === 1;
}
?>