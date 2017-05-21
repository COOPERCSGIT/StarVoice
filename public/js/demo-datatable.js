// Demo datatables
// ----------------------------------- 
(function(window, document, $, undefined){
	function toUtf8(str) {    
	    var out, i, len, c;    
	    out = "";    
	    len = str.length;    
	    for(i = 0; i < len; i++) {    
	        c = str.charCodeAt(i);    
	        if ((c >= 0x0001) && (c <= 0x007F)) {    
	            out += str.charAt(i);    
	        } else if (c > 0x07FF) {    
	            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));    
	            out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));    
	            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));    
	        } else {    
	            out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));    
	            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));    
	        }    
	    }    
	    return out;    
	} 
  $(function(){
    //
    // Zero configuration
    // 
	var aurl = url=='' ? location.href+'?_ajax=1' : url;
	console.log(aurl);
	if(aoColumns){
	    var table = $('#datatable1').dataTable({
	        'paging':   true,  // Table pagination
	        'ordering': true,  // Column ordering 
	        'info':     true,  // Bottom left status text
	        // Text translation options
	        // Note the required keywords between underscores (e.g _MENU_)
	        sAjaxSource: aurl,
	        oLanguage: {
	            sSearch:      '查找:',
	            sLengthMenu:  '_MENU_条显示',
	            sEmptyTable: "未有相关数据",
	            sLoadingRecords: "正在加载数据-请稍等...",
	            info:         '显示 _PAGE_ 到 _PAGES_ 条',
	           	sZeroRecords: "对不起，查询不到任何相关数据",
	           	sInfoEmpty: "当前显示 0 到 0 条，共 0 条记录",
	            sInfoFiltered: "（数据库中共为 _MAX_ 条记录）",
	            infoEmpty:    '没有找到匹配的记录',
	            infoFiltered: '(从 _MAX_ 过滤)',
	        },
	        "aoColumns": aoColumns,
	        "initComplete": function(settings, json) {
			    $(".qrcode").each(function(){		
					var txt = toUtf8("谢谢支持正版， 您的防伪码编号："+$(this).text());
					$(this).text("");
					$(this).qrcode(txt);
					
				});
			},
	    });
	    
	    
	}
    
    // 初始化行内按钮  
    $('#datatable1 tbody').on('click', 'a.btn-del,a.btn-other', function(e) {    		
		var obj = $(this);
		var tables = $('#datatable1').DataTable();
		if(obj.hasClass('btn-del')){
			swal({
				title: "操作提示",
				text: "不能恢复的删除，确定吗？",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "确定, 删除!",
				closeOnConfirm: false
			}, function() {
				$.post(obj.attr('data-url'),'',function(data){
					if(data.type=='success') tables.row(obj.parents('tr')).remove().draw();
					swal("提示", data.info, data.type);
				})
			});
		}else{
			$.post(obj.attr('data-url'),'',function(data){
				var tables = $('#datatable1').dataTable();
				tables.fnReloadAjax();
				if(obj.attr('data-s')=='status'){
					var snum = $(".pull-right").html().replace(/[^0-9]/ig,"");
					$(".pull-right").html('待审'+(snum-1));
				}
				swal("提示", data.info, data.type);
			})
		}
    }); 
    
    // 批量操作 
    $('.del-all,.other-all').on('click', function(e) {    		
		var obj = $(this);
		var codes = '0';			
		$("input[type=checkbox]:checked").each(function(){
			var code = $(this).val();
			if(code!='on') codes+= ','+code;
		});
		if(codes=='0'){swal("提示", "请至少选择一条记录", "error"); return false;}
		
		if(obj.hasClass('other-all')){
			otherpost(obj,codes);
		}else{
			delpost(obj,codes);
		}
    }); 
		
	$('.colora').click(function(){
		var code = '';
		$('.buall input').each(function(){
			if($(this).val() != '') code += ' ' + $(this).val();
		});
		table.fnFilter(code);
	})
	
	var delpost = function(obj,codes){
		swal({
			title: "操作提示",
			text: "不能恢复的删除，确定吗？",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "确定, 删除!",
			closeOnConfirm: false
		}, function() {
			$.post(obj.attr('data-url'),{id:codes},function(data){
				if(data.type=='success') {
					var tables = $('#datatable1').DataTable();
					$("input[type=checkbox]:checked").each(function(){
						var code = $(this).val();
						if(code!='on') tables.row($(this).parents('tr')).remove().draw();
					});
				}
				swal("提示", data.info, data.type);
			})
		});
	}
	
	var otherpost = function(obj,codes){		
		$.post(obj.attr('data-url'),{id:codes},function(data){
			if(data.type=='success') {
				var tables = $('#datatable1').dataTable();
				tables.fnReloadAjax();
			}
			swal("操作提示", data.info, data.type);
		})
	}

    // 
    // Filtering by Columns
    // 

    var dtInstance2 = $('#datatable2').dataTable({
        'paging':   true,  // Table pagination
        'ordering': true,  // Column ordering 
        'info':     true,  // Bottom left status text
        // Text translation options
        // Note the required keywords between underscores (e.g _MENU_)
        oLanguage: {
            sSearch:      'Search all columns:',
            sLengthMenu:  '_MENU_ records per page',
            info:         'Showing page _PAGE_ of _PAGES_',
            zeroRecords:  'Nothing found - sorry',
            infoEmpty:    'No records available',
            infoFiltered: '(filtered from _MAX_ total records)'
        }
    });
    var inputSearchClass = 'datatable_input_col_search';
    var columnInputs = $('tfoot .'+inputSearchClass);

    // On input keyup trigger filtering
    columnInputs
      .keyup(function () {
          dtInstance2.fnFilter(this.value, columnInputs.index(this));
      });


    // 
    // Column Visibilty Extension
    // 

    $('#datatable3').dataTable({
        'paging':   true,  // Table pagination
        'ordering': true,  // Column ordering 
        'info':     true,  // Bottom left status text
        // Text translation options
        // Note the required keywords between underscores (e.g _MENU_)
        oLanguage: {
            sSearch:      'Search all columns:',
            sLengthMenu:  '_MENU_ records per page',
            info:         'Showing page _PAGE_ of _PAGES_',
            zeroRecords:  'Nothing found - sorry',
            infoEmpty:    'No records available',
            infoFiltered: '(filtered from _MAX_ total records)'
        },
        // set columns options
        'aoColumns': [
            {'bVisible':false},
            {'bVisible':true},
            {'bVisible':true},
            {'bVisible':true},
            {'bVisible':true}
        ],
        sDom:      'C<"clear">lfrtip',
        colVis: {
            order: 'alfa',
            'buttonText': 'Show/Hide Columns'
        }
    });

    // 
    // AJAX
    // 

    $('#datatable4').dataTable({
        'paging':   true,  // Table pagination
        'ordering': true,  // Column ordering 
        'info':     true,  // Bottom left status text
        sAjaxSource: '../server/datatable.json',
        aoColumns: [
          { mData: 'engine' },
          { mData: 'browser' },
          { mData: 'platform' },
          { mData: 'version' },
          { mData: 'grade' }
        ]
    });
  });

})(window, document, window.jQuery);
