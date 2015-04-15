$('#previous-url').change(function(){
    $('input[name="url"]').val($(this).val());
});

$('#crawler-status').on('click', '.js-link', function(){
    scrollToId($(this).attr('rel'));
});

function processCrawlerURL()
{
    var url = $('input[name="url"]').val();
    var urlId = url.replace(/[^_\-a-zA-Z0-9]/g, '');
    var depth = parseInt($('input[name="depth"]').val()) || 0;
    if ($('#'+urlId+'_'+depth).length)
    {
	scrollToId(urlId+'_'+depth);
	return false;
    }
    
    informUser(url+': Crawling started, please wait...');
    $('#crawler-results').append('<div id="'+urlId+'_'+depth+'" class="result-block">Please wait....<br/>This can take a while.</div>');
    var start = new Date().getTime();
    $.ajax({url: 'ajax.php',
	    method: 'POST',
	    data: {controller: 'crawler',
		   action: 'ajaxCrawlURL',
		   url: url,
		   depth: depth}
	   }).done(function(data){
	       var end = new Date().getTime();
	       var time = (end - start) / 1000;
	       displayLinks(urlId+'_'+depth, data);
	       informUser(url+': Crawling is over.', urlId+'_'+depth);
	       informUser(url+': Execution time: '+(time > 60 ? Math.floor(time / 60)+' min '+ Math.floor(time % 60) : time)+' sec');

	   }).fail(function(data){
	       informUser(url+': Failure to crawl.');
	   })
    return false;
}

function informUser(text, id)
{
    var status = $('#crawler-status');
    var lt = /</g, gt = />/g, ap = /'/g, ic = /"/g;
    text = text.replace(lt, "&lt;").replace(gt, "&gt;").replace(ap, "&#39;").replace(ic, "&#34;");
    status.append('<p>'+text+(id != undefined ? ' <span class="js-link" rel="'+id+'">Scroll to result</span>': '')+'</p>');
}

function displayLinks(id, data)
{
    $('#'+id).html(data);
    displayPie(id);
}

function scrollToId(id)
{
    $('html, body').animate({scrollTop: $('#'+id).offset().top+'px'}, 'fast');
}

function displayPie(id)
{
    var pieElement = $('#'+id).find('.pieData');
    var pieValue = parseFloat(pieElement.attr('rel'));
    var otherValue = (100 - pieValue);
    var pieData = [
	{
	    value: pieValue,
	    color:"#F7464A",
	    highlight: "#FF5A5E",
	    label: pieElement.text()+'(%)'
	},
	{
	    value: otherValue,
	    color: "#46BFBD",
	    highlight: "#5AD3D1",
	    label: "Others (%)"
	}
    ];
    if (pieValue > 0)
    {
	var ctx = $('#'+id+' .canvas')[0].getContext("2d");
	$('#'+id+' .canvas').after('<span style="color:#F7464A">URL from host</span><span style="color: #46BFBD">URL outside host</span>')
	window.myPie = new Chart(ctx).Pie(pieData);
    }
    else
	$('#'+id+' .canvas-div').html('No Links were found');
    
}
