"use strict";

/* begin func defs */

function format_iec(bytes, sf, prec) {
	
	sf = typeof sf !== 'undefined' ? sf : 3;
	prec = typeof prec !== 'undefined' ? prec : 0;
	
	var iec = [];
	iec.push({name: 'Bytes', suf: 'B', bytes: 1});
	iec.push({name: 'Kibibyte', suf: 'KiB', bytes: 1024});
	iec.push({name: 'Mebibyte', suf: 'MiB', bytes: 1048576});
	iec.push({name: 'Gibibyte', suf: 'GiB', bytes: 1073741824});
	iec.push({name: 'Tebibyte', suf: 'TiB', bytes: 1099511627776});
	iec.push({name: 'Pebibyte', suf: 'PiB', bytes: 1125899906842624});
	iec.push({name: 'Exbibyte', suf: 'EiB', bytes: 1152921504606846976});
	iec.push({name: 'Zebibyte', suf: 'ZiB', bytes: 1180591620717411303424});
	iec.push({name: 'Yobibyte', suf: 'YiB', bytes: 1208925819614629174706176});

	
	var size;
	var suffix;
	
	$.each(iec, function(e) {
		size = bytes / e.bytes;
		suffix = e.suf;
		console.log('E: '+e);
		console.log('Size: '+size);
		console.log('Suffix: '+suffix);
		return(size > 1023);
	});
	
	var s = Math.round(size);
	return (''+s+' '+suffix);
}

/* begin main prog */
$(document).ready(function() {

		$.ajax({
			dataType: "json",
			//type: 'POST',
			contentType: "application/json",      
			url: self.location.href,
			headers: {
				        'Accept': 'application/json',
				        'Content-Type': 'application/json' 
			},
			data: "/"
		}).done(function(data) {
				$.each(data, function(i, item) {
						$("<p>"+i+' : '+format_iec(item)+"</p>").appendTo("#main-container");
				});
		});
});