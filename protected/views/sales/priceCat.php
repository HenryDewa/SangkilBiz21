<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createPCat'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deletePCat'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    
    $('#dg').mdmegrid('unselectAll');
    $('#MditemCategory_cdicat').removeAttr('readonly');
    $('#MditemCategory_cdicat').focus();
    $('#MditemCategory_cdicat').val('');
    $('#MditemCategory_dscrp').val('');
        
    $('#delBtn').linkbutton('disable');        
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#sales-pcat-form').serializeArray();
            $ajaxsimpan
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#MditemCategory_cdicat').removeAttr('readonly');
    $('#MditemCategory_cdicat').val('');
    $('#MditemCategory_dscrp').val(''); 
        
    $('#delBtn').linkbutton('disable');
    $('#saveBtn').linkbutton('disable');
});        
   
$('#delBtn').click(function(){
    var bisa = $('#delBtn').linkbutton('options');
    if(bisa.disabled) return false;

    var data = $('#dg').mdmegrid('getSelections');
    var jmldata = data.length;
    var pesan = 'Delete '+jmldata+' selected data, Are you sure?';
    if(jmldata>0){
        if (!confirm(pesan)) return false;
	else{
            $ajaxdelete
            return false;
	}
    }
    else alert('No selected row..!');
});
        
$('#MditemCategory_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#MditemCategory_cdicat').focus();
     }
 });
        
function clickRow(){
    $('#MditemCategory_cdicat').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#MditemCategory_dscrp').focus();
    $('#MditemCategory_dscrp').select($('#MditemCategory_dscrp').length);
}
           
function sukses(r,sender){
    if(r!=''){ 
        alert(r);
        return true;
    } 
        
    if(sender == 'delete') 
        $('#cancelBtn').click();
    else
        $('#newBtn').click();
        
    $('#dg').mdmegrid('load');
}

function failed(r,sender){
    alert('Failed on '+sender+' record..!');
}
");
?>
<?php
if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "sales";

        $judul = "Price Category";
$this->pageTitle = Yii::app()->name . " - $judul";

$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'sales-pcat-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdpcat'); ?></td>
                <td>
                    <?php echo $form->textField($model, 'cdpcat', array('size' => 13, 'maxlength' => 13)); ?>            
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td><?php echo $form->textField($model, 'dscrp', array('size' => 32, 'maxlength' => 32)); ?>            </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
            </tr>
        </table>
    </div><!-- form -->
                <?php
        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dg',
            'dataUrl' => array('dataPCat'),
            'options' => array(
                'pagination' => true,
                'rownumbers' => true,
                'onSelect' => 'js:function(index,row){clickRow();}',
                'pageSize' => 10,
                'singleSelect' => true,
            ),
            'columns' => array(
                array('field' => 'cdpcat', 'title' => 'Code',
                    'htmlOptions' => array('width' => 100, 'align' => 'center'),
                    'selector' => '#MdpriceCat_cdpcat'),
                array('field' => 'dscrp', 'title' => 'Description',
                    'htmlOptions' => array('width' => 500),
                    'selector' => '#MdpriceCat_dscrp'),
                array('field' => 'update_date',
                    'title' => 'Last Update',
                    'htmlOptions' => array('width' => 200),),
            ),
            'htmlOptions' => array(
                'fitColumns' => "true",
                'style' => "width:707px;height:300px;padding-top:0.2em;",
            )
        ));
        ?>

        <?php $this->endWidget(); ?>

</div>