$('#previous-url').change(function(){
    $('input[name="url"]').val($(this).val());
});

$('#crawler-status').on('click', '.js-link', function(){
    scrollToId($(this).attr('rel'));
});

function processCrawlerURL()
{
    var url = $('input[name="url"]').val();
    var urlId = url.replace(/\/|:|\./g, '');
    var depth = $('input[name="depth"]').val() || 0;
    if ($('#'+urlId+'_'+depth).length)
    {
	scrollToId(urlId+'_'+depth);
	return false;
    }
    
    informUser(url+': Crawling started, please wait...');
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
    status.append('<p>'+text+(id != undefined ? ' <span class="js-link" rel="'+id+'">Scroll to result</span>': '')+'</p>');
}

function displayLinks(id, data)
{
    var results = $('#crawler-results');
    results.append('<div id="'+id+'" class="result-block">'+data+'</div>');
    displayPie(id);
}

function scrollToId(id)
{
    $('html, body').animate({scrollTop: $('#'+id).offset().top+'px'}, 'fast');
}

function displayPie(id)
{
    var pieElement = $('#'+id).find('.pieData');
    var pieValue = parseInt(pieElement.attr('rel'));
    var otherValue = (100 - pieValue);
    var pieData = [
	{
	    value: pieValue,
	    color:"#F7464A",
	    highlight: "#FF5A5E",
	    label: pieElement.text()
	},
	{
	    value: otherValue,
	    color: "#46BFBD",
	    highlight: "#5AD3D1",
	    label: "Others"
	}
    ];
    
    var ctx = $('#'+id+' .canvas')[0].getContext("2d");
    $('#'+id+' .canvas').after('<span style="color:#F7464A">'+pieElement.text()+'</span><span style="color: #46BFBD">Others</span>')
    window.myPie = new Chart(ctx).Pie(pieData);
}