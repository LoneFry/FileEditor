/******************************************************************************
 * File        : ajas_tabbing.js
 * Created By  : LoneFry
 * License     : CC BY-NC-SA
 *                Creative Commons Attribution-NonCommercial-ShareAlike
 *                http://creativecommons.org/licenses/by-nc-sa/3.0/
 * Created on  : Jan 9, 2009
 *                Formalized from some other inline scripts I'd done
 *****************************************************************************/
if(!ajas)var ajas={'form':{}};
if(!ajas.form)ajas.form={};

//a flag to cache whether we are in an IE browser.
//set by conditional comments...
if(undefined==ajas._bIE){
	ajas._bIE=false;
	document.write('<!--[if IE]><script type="text/javascript">ajas._bIE=true;'
//	+'ajas.util.addLoadHandler(function(){document.body.className+=" bIE";});'
	+'</scr'+'ipt><![endif]-->');
}

ajas.form.IE_setSelectionRange=function(oTextarea, iStart, iEnd){
	if (!ajas._bIE)return;
	var oRng = oTextarea.createTextRange();
	oRng.collapse(true);
	oRng.moveStart("character", iStart);
	oRng.moveEnd("character", iEnd - iStart);
	oRng.select();
}
ajas.form.IE_hackPara=function(oTextarea) {
	//to hack around an IE quirk, I am making the gross assumption
	// that no one would have a &#28; in their text
	if (!ajas._bIE)return;
	if (oTextarea.value.charCodeAt(oTextarea.value.length-1)==13
	 || oTextarea.value.charCodeAt(oTextarea.value.length-1)==10) {
		oTextarea.value=oTextarea.value.replace(/\034/g,'')+String.fromCharCode(28);
		ajas.form.IE_setSelectionRange(oTextarea,oTextarea.value.length-2,oTextarea.value.length-2);
	}
}
ajas.form.IE_unHackPara=function(oTextarea) {
	//to hack around an IE quirk, I am making the gross assumption
	// that no one would have a &#28; in their text
	if (!ajas._bIE)return;
	oTextarea.value=oTextarea.value.replace(/\034/g,'');
}
ajas.form.simpleTab=function(oTextarea,event) {
	event=event||window.event;
	var sTab='\t';

	if (ajas._bIE) {
		//handle tabs IE
		if (9 == event.keyCode) {
			oTextarea.focus();
			var oRng = document.selection.createRange();
			oRng.text=sTab;
			oRng.select();
			return false;
		}
	} else {
		//handle tabs FF
		if (9 == event.which) {
			var iStart = oTextarea.selectionStart;
			var iEnd   = oTextarea.selectionEnd;
			oTextarea.focus();
			oTextarea.value = oTextarea.value.substring(0, iStart)
				+sTab
				+oTextarea.value.substring(iEnd);
			oTextarea.setSelectionRange(iStart+sTab.length,iStart+sTab.length);
			return false;
		}
	}
}
ajas.form.multiLineTab=function(oTextarea,event) {
	event=event||window.event;
	var sTab='\t';

	if (ajas._bIE) {
		//the below method almost worked, except that IE likes to
		//ignore trailing linebreaks, that's why I quietly add the
		//&#28; FIRST thing in this function...or on keyup
		var oRng = document.selection.createRange();
		var oRng2 = oRng.duplicate();
		oRng2.moveToElementText(oTextarea);
		oRng2.setEndPoint('StartToEnd', oRng);
		iEnd = oTextarea.value.length-oRng2.text.length;
		oRng2.setEndPoint('StartToStart', oRng);
		iStart = oTextarea.value.length-oRng2.text.length;

		//handle tabs IE
		if (9 == event.keyCode) {
			oTextarea.focus();
			var sNewText=sTab;

			var aText=oTextarea.value.substring(iStart,iEnd).split('\n');
			if (aText.length > 1) { //multi-line selection!
				iStart = oTextarea.value.lastIndexOf('\n',iStart+1);
				if (iStart == -1) iStart=0;
				if (oTextarea.value.charCodeAt(iEnd)==13)iEnd-=1;
				var sBefore=oTextarea.value.substring(0, iStart);
				var sOldText=oTextarea.value.substring(iStart,iEnd);
				var sAfter=oTextarea.value.substring(iEnd);
				aText=sOldText.split('\n');
				if (event.shiftKey) {
					sNewText=sOldText.replace(new RegExp('\n'+sTab,'g'),'\n').replace(new RegExp('^'+sTab,''),'');
				} else {
					sNewText=(iStart==0?sTab:'')+aText.join('\n'+sTab);
				}

				oTextarea.value = sBefore + sNewText + sAfter;
				ajas.form.IE_setSelectionRange(oTextarea, iStart-(sBefore.split('\n').length-1)
					,iStart+sNewText.length-((sBefore+sNewText).split('\n').length-1));
			} else {
				oRng.text=sTab;
				oRng.select();
			}
			return false;
		} else

		//handle returns IE
		if (13 == event.keyCode) {
			if (iStart==0) return true;
			if (oTextarea.value.substr(iStart-1,0).charCodeAt(0) == 13) return true;

			var iPrevLine = oTextarea.value.substring(0, iStart).lastIndexOf('\n');
			if (iPrevLine == -1) iPrevLine=0;

			var aBits=/^\n?([\t ]*)[^\t ]?/.exec(oTextarea.value.substring(iPrevLine, iStart));
			if (!aBits || aBits[1].length==0)return true;

			var sBefore=oTextarea.value.substring(0, iStart);
			oTextarea.value = sBefore
				+'\n'+aBits[1]
				+oTextarea.value.substring(iEnd);
			ajas.form.IE_setSelectionRange(oTextarea, (sBefore+aBits[1]).length+2-sBefore.split('\r').length
				,(sBefore+aBits[1]).length+2-sBefore.split('\r').length);

			return false;
		}
	} else {
		var iStart = oTextarea.selectionStart;
		var iEnd   = oTextarea.selectionEnd;
		//handle tabs FF
		if (9 == event.which) {
			oTextarea.focus();
			var sNewText=sTab;

			var aText=oTextarea.value.substring(iStart,iEnd).split('\n');
			if (aText.length > 1) { //multi-line selection!
				iStart = oTextarea.value.lastIndexOf('\n',iStart+1);
				if (iStart == -1) iStart=0;
				if (oTextarea.value.charCodeAt(iEnd)==10)iEnd-=1;
				var sBefore=oTextarea.value.substring(0, iStart);
				var sOldText=oTextarea.value.substring(iStart,iEnd);
				var sAfter=oTextarea.value.substring(iEnd);
				aText=sOldText.split('\n');
				if (event.shiftKey) {
					sNewText=sOldText.replace(new RegExp('\n'+sTab,'g'),'\n').replace(new RegExp('^'+sTab,''),'');
				} else {
					sNewText=(iStart==0?sTab:'')+aText.join('\n'+sTab);
				}

				oTextarea.value = sBefore + sNewText + sAfter;
				oTextarea.setSelectionRange(iStart,iStart+sNewText.length);
			} else {
				oTextarea.value = oTextarea.value.substring(0, iStart)
					+sTab
					+oTextarea.value.substring(iEnd);
				oTextarea.setSelectionRange(iStart+sTab.length,iStart+sTab.length);
			}
			return false;
		} else

		//handle returns FF
		if (13 == event.which) {
			if (iStart==0) return true;

			var iPrevLine = oTextarea.value.substring(0, iStart).lastIndexOf('\n');
			if (iPrevLine == -1) iPrevLine=0;

			var aBits=/^\n?([\t ]*)[^\t ]?/.exec(oTextarea.value.substring(iPrevLine, iStart));
			if (!aBits || aBits[1].length==0)return true;

			oTextarea.value = oTextarea.value.substring(0, iStart)
				+'\n'+aBits[1]
				+oTextarea.value.substring(iEnd);
			oTextarea.setSelectionRange(iStart+aBits[1].length+1,iStart+aBits[1].length+1);

			return false;
		}
	}
}
