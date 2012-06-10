

function getObject(objectId) { return document.getElementById(objectId); }
function getObjectStyle(objectId) { var obj = getObject(objectId); return obj.style;}

var page_loaded = false;

function kpe_generate() { document.profile.submit(); }

function kpe_update(property, value)
{
	if (page_loaded) {
	
		var ms_body		= getObjectStyle("mysbody");
		var ms_table1	= getObjectStyle("mystable1");
		var ms_table2	= getObjectStyle("mystable2");
		var ms_table3	= getObjectStyle("mystable3");
		var ms_table4	= getObjectStyle("mystable4");
        var ms_table5	= getObjectStyle("mystable5");
		var ms_name		= getObjectStyle("mysname");
		var ms_title	= getObjectStyle("mystitle");
		var ms_text		= getObjectStyle("mystext");
		var ms_link		= getObjectStyle("myslink");
		
		switch(property)
		{
			// Body Background
			case 'backgroundColor':				ms_body.backgroundColor = value; break;
			case 'backgroundImage':				ms_body.backgroundImage = "url(" + value + ")"; break;
			case 'backgroundPosition':			ms_body.backgroundPosition = value;
			break;
			case 'backgroundRepeat':			ms_body.backgroundRepeat = value; break;
			case 'backgroundAttachment':		ms_body.backgroundAttachment = value; break;
			// Sections (tables)
			case 'sectionBackgroundColor':		ms_table1.backgroundColor = ms_table2.backgroundColor = ms_table3.backgroundColor= ms_table4.backgroundColor = ms_table5.backgroundColor= value; break;
			case 'sectionBorderColor':			ms_table1.borderColor = ms_table3.borderColor = ms_table4.borderColor = ms_table5.borderColor = value; break;
			case 'sectionBorderWidth':			ms_table1.borderWidth = ms_table3.borderWidth = ms_table4.borderWidth = ms_table5.borderWidth = value + "px"; ms_table1.width = ms_table2.width = ms_table3.width = ms_table4.width = ms_table5.width = (170 - (value * 2)) + "px"; break;
			case 'sectionBorderStyle':			ms_table1.borderStyle = ms_table3.borderStyle = ms_table4.borderStyle= ms_table5.borderStyle= value; break;
			case 'sectionOpacity':				if (navigator.appName.indexOf("Netscape")!=-1 && parseInt(navigator.appVersion) >= 5) { ms_table1.MozOpacity = ms_table2.MozOpacity = ms_table3.MozOpacity = ms_table4.MozOpacity = ms_table5.MozOpacity= value/100; } else if (navigator.appName.indexOf("Microsoft") != -1 && parseInt(navigator.appVersion)>=4) { ms_table1.filter = ms_table2.filter = ms_table3.filter = ms_table4.filter = ms_table5.filter= "alpha(opacity=" + value + ")"; } break;
			// Name Text
			case 'nameFontFamily':				ms_name.fontFamily = value; break;
			case 'nameFontSize':				ms_name.fontSize = value + "px"; break;
			case 'nameFontColor':				ms_name.color = value; break;
			case 'nameFontWeight':				if (getObject("in_nameFontWeight").value == "normal") { ms_name.fontWeight = value; getObject("nfso1").className = "selected"; } else { value = "normal"; ms_name.fontWeight = value; getObject("nfso1").className = ""; } break;
			case 'nameFontStyle':				if (getObject("in_nameFontStyle").value == "normal") { ms_name.fontStyle = value; getObject("nfso2").className = "selected"; } else { value = "normal"; ms_name.fontStyle = value; getObject("nfso2").className = ""; } break;
			case 'nameFontDecoration':			if (getObject("in_nameFontDecoration").value == "none") { ms_name.textDecoration = value; getObject("nfso3").className = "selected"; } else { value = "none"; ms_name.textDecoration = value; getObject("nfso3").className = ""; } break;
			// Headings
			case 'headingFontFamily':			ms_title.fontFamily = value; break;
			case 'headingFontSize':				ms_title.fontSize = value + "px"; break;
			case 'headingFontColor':			ms_title.color = value; break;
			case 'headingFontWeight':			if (getObject("in_headingFontWeight").value == "normal") { ms_title.fontWeight = value; getObject("hfso1").className = "selected"; } else { value = "normal"; ms_title.fontWeight = value; getObject("hfso1").className = ""; } break;
			case 'headingFontStyle':			if (getObject("in_headingFontStyle").value == "normal") { ms_title.fontStyle = value; getObject("hfso2").className = "selected"; } else { value = "normal"; ms_title.fontStyle = value; getObject("hfso2").className = ""; } break;
			case 'headingFontDecoration':		if (getObject("in_headingFontDecoration").value == "none") { ms_title.textDecoration = value; getObject("hfso3").className = "selected"; } else { value = "none"; ms_title.textDecoration = value; getObject("hfso3").className = ""; } break;
			// Main Text
			case 'fontFamily':					ms_text.fontFamily = ms_link.fontFamily = value; break;
			case 'fontSize':					ms_text.fontSize = ms_link.fontSize = value + "px"; break;
			case 'fontColor':					ms_text.color = value; break;
			case 'fontWeight':					if (getObject("in_fontWeight").value == "normal") { ms_text.fontWeight = value; getObject("fso1").className = "selected"; } else { value = "normal"; ms_text.fontWeight = value; getObject("fso1").className = ""; } break;
			case 'fontStyle':					if (getObject("in_fontStyle").value == "normal") { ms_text.fontStyle = value; getObject("fso2").className = "selected"; } else { value = "normal"; ms_text.fontStyle = value; getObject("fso2").className = ""; } break;
			case 'fontDecoration':				if (getObject("in_fontDecoration").value == "none") { ms_text.textDecoration = value; getObject("fso3").className = "selected"; } else { value = "none"; ms_text.textDecoration = value; getObject("fso3").className = ""; } break;
			// Links
			case 'linkFontColor':				ms_link.color = value; break;
			case 'linkFontWeight':				if (getObject("in_linkFontWeight").value == "normal") { ms_link.fontWeight = value; getObject("lso1").className = "selected"; } else { value = "normal"; ms_link.fontWeight = value; getObject("lso1").className = ""; } break;
			case 'linkFontStyle':				if (getObject("in_linkFontStyle").value == "normal") { ms_link.fontStyle = value; getObject("lso2").className = "selected"; } else { value = "normal"; ms_link.fontStyle = value; getObject("lso2").className = ""; } break;
			case 'linkFontDecoration':			if (getObject("in_linkFontDecoration").value == "none") { ms_link.textDecoration = value; getObject("lso3").className = "selected"; } else { value = "none"; ms_link.textDecoration = value; getObject("lso3").className = ""; } break;
			// Links (hover)
			case 'linkHoverFontColor':			/* */ break;
			case 'linkHoverFontWeight':			if (getObject("in_linkHoverFontWeight").value == "normal") { getObject("lhso1").className = "selected"; } else { value = "normal"; getObject("lhso1").className = ""; } break;
			case 'linkHoverFontStyle':			if (getObject("in_linkHoverFontStyle").value == "normal") { getObject("lhso2").className = "selected"; } else { value = "normal"; getObject("lhso2").className = ""; } break;
			case 'linkHoverFontDecoration':		if (getObject("in_linkHoverFontDecoration").value == "none") { getObject("lhso3").className = "selected"; } else { value = "none"; getObject("lhso3").className = ""; } break;
		}
		getObject("in_" + property).value = value;
	}
}

function kpe_link(status)
{
	var status = (status != "") ? "Hover" : "";
	
	var ms_link = getObjectStyle("myslink");
	
	ms_link.color			= getObject("in_link" + status + "FontColor").value;
	ms_link.fontWeight		= getObject("in_link" + status + "FontWeight").value;
	ms_link.fontStyle		= getObject("in_link" + status + "FontStyle").value;
	ms_link.textDecoration	= getObject("in_link" + status + "FontDecoration").value;
}

function kpe_align_bg(id)
{
	var options = new Array("", "left top", "center top", "right top", "left center", "center center", "right center", "left bottom", "center bottom", "right bottom");
	var selected = parseFloat(id.substring(4));
	var selected_value = options[selected];
	
	for(i=1; i < options.length; i++) getObject("bgao" + i).className = null;
	
	getObject(id).className = "selected";
	
	kpe_update('backgroundPosition', selected_value);
}

function kpe_transparent_section()
{
	var color = getObject("in_sectionBackgroundColor");
	var picker = getObject("s_sbgc-wrapper");
	
	if(color.value == "transparent")
	{
		var rgb = c_sbgc.getRgb();
		kpe_update("sectionBackgroundColor", "rgb(" + rgb.r + ", " + rgb.g + ", " + rgb.b + ")");
		picker.style.display = "block";
	}
	else
	{
		kpe_update("sectionBackgroundColor", "transparent");
		picker.style.display = "none";
	}
}