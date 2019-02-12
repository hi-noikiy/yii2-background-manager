<button type="button" class="layui-btn" id="test1">
    <i class="layui-icon">&#xe67c;</i>上传图片
</button>

<script>
    layui.use(['upload', 'layer'], function(){
        var upload = layui.upload;

        var uploadInst = upload.render({
            elem: '#test1'
            ,url: '/game-set/t2'
            ,done: function(res) {
                console.log(res);
                if (res.code == 0) {
                    layer.msg('上传成功');
                }
            }
            ,error: function() {

            }
        });
    });
</script>

<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'f_price')->fileInput() ?>

<button>Submit</button>

<?php ActiveForm::end() ?>