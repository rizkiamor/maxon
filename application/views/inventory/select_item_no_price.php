 
	<table width="100%" class="table2">
		<tr>
			<td>Kode Barang</td><td>Nama Barang</td><td>Qty</td><td>Unit</td><td>Button</td>
		</tr>
		<tr>
				 <td><input onblur='find()' id="item_number" style='width:80px' 
					name="item_number"   class="easyui-validatebox" required="true">
					<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" 
					onclick="searchItem();return false;"></a>
				 </td>
				 <td><input id="description" name="description" style='width:280px'></td>
				 <td><input id="quantity"  style='width:30px'  name="quantity"  ></td>
				 <td><input id="unit" name="unit"  style='width:30px' ></td>

				<td><a href="#" class="easyui-linkbutton" data-options="iconCls:'icon-save'"  
				   plain='true'	onclick='save_item();return false;'>Add Item</a>
				</td>
				<input type='hidden' id='ref_number' name='ref_number'>
				<input type='hidden' id='line_number' name='line_number'>
		</tr>
	</table>
	
	
<div id='dlgSearchItem'class="easyui-dialog" style="width:500px;height:380px;padding:10px 20px"
        closed="true" toolbar="#tb_search">
     <div id='divItemSearchResult'> 
		<table width="100%" id="dgItemSearch" class="easyui-datagrid"  
			data-options="
				toolbar: 'tb_item',fitColumns:true,
				singleSelect: true,
				url: '<?=base_url()?>index.php/inventory/filter'
			">
			<thead>
				<tr>
					<th data-options="field:'description',width:150">Nama Barang</th>
					<th data-options="field:'item_number',width:80">Kode Barang</th>
				</tr>
			</thead>
		</table>
    </div>   
</div>	   
	
<div id="tb_item">
	<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editItem()">Edit</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="deleteItem()">Delete</a>	
</div> 

<div id="tb_search" style="height:auto">
	Enter Text: <input  id="search_item" style='width:180px' 
 	name="search_item">
	<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" 
	onclick="searchItem();return false;"></a>        
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="selectSearchItem();return false;">Select</a>
</div>


<script language="JavaScript"> 
	function deleteItem(){
		var row = $('#'+grid_output).datagrid('getSelected');
		if (row){
			$.messager.confirm('Confirm','Are you sure you want to remove this line?',function(r){
				if (r){
					$.post(url_del_item,{line_number:row.line_number},function(result){
						if (result.success){
							$('#'+grid_output).datagrid('reload');	// reload the user data
						} else {
							log_err(result.msg);
						}
					},'json');
				}
			});
		}
	}
	function editItem(){
		var row = $('#'+grid_output).datagrid('getSelected');
		if (row){
			console.log(row);
			$('#frmItem').form('load',row);
			$('#item_number').val(row.item_number);
			$('#description').val(row.description);
			$('#quantity').val(row.from_qty);
			///$('#from_qty').val(row.quantity);
			$('#unit').val(row.unit);
			$('#line_number').val(row.line_number);
		}
	}
		function save_item(){
			$('#frmItem').form('submit',{
				url: url_save_item,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.success){
						log_msg("Data sudah tersimpan.");
						$('#'+grid_output).datagrid({url:url_load_item});
						$('#'+grid_output).datagrid('reload');
						$('#item_number').val('');
						$('#unit').val('Pcs');
						$('#description').val('');
						$('#line_number').val('');
						$('#quantity').val('1');
					} else {
						log_err(result.msg);
					}
				}
			});
		}
		function selectSearchItem()
		{
			var row = $('#dgItemSearch').datagrid('getSelected');
			if (row){
				$('#item_number').val(row.item_number);
				$('#description').val(row.description);
				find();
				$('#dlgSearchItem').dialog('close');
			}
			
		}
		function searchItem()
		{
			$('#dlgSearchItem').dialog('open').dialog('setTitle','Cari data barang');
			nama=$('#search_item').val();
			xurl='<?=base_url()?>index.php/inventory/filter/'+nama;
			$('#dgItemSearch').datagrid({url:xurl});
			$('#dgItemSearch').datagrid('reload');
		}
		function find(){
		    xurl=CI_ROOT+'inventory/find/'+$('#item_number').val();
		    $.ajax({
		                type: "GET",
		                url: xurl,
		                data:'item_no='+$('#item_number').val(),
		                success: function(msg){
		                    var obj=jQuery.parseJSON(msg);
		                    $('#item_number').val(obj.item_number);
		                    $('#unit').val(obj.unit_of_measure);
		                    $('#description').val(obj.description);
		                    $('#quantity').val(1);
		                },
		                error: function(msg){alert(msg);}
		    });
		};

</script>
