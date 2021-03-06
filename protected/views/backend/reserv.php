<head>
    <link rel="stylesheet" type="text/css" href="/css/datepicker.back.css" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.js"></script> 
    <script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="/js/bootstrap-datepicker.ru.js"></script>
    <?php $this->pageTitle = 'Запись - '.Yii::app()->name; ?>
</head>



<?php
$form = $this->beginWidget('CActiveForm', array(
    'id'                   => 'reserv',
    'enableAjaxValidation' => false,
        ));
?>

<div class="page-header">
    <h1 style="display: inline;">Запись</h1> 
</div>

<div class="col-lg-6" id="date">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Выберите дату:</h3>
        </div>

        <div class="panel-body">
            <div class="form-group input-group">
                <?php echo CHtml::textField('date', date("d-m-Y", $date['num']), array('class' => 'form-control', 'id' => 'datepicker')); ?>  
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title lead"><i class="fa fa-calendar-o fa-fw"></i> <strong><?php echo $date['string']; ?></strong></h3>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <?php
                        for ($time = $date['time_start']; $time <= $date['time_end']; $time = $time + ($date['period'] * 60))
                        {
                            $status = 'free';
                            if (isset($reserved))
                                foreach ($reserved as $reserv)
                                {
                                    if ($time >= $reserv['time_start'] && $time < $reserv['time_end'] && $reserv['status'] == 'reserved')
                                        $status = 'reserved';
                                    if ($time >= $reserv['time_start'] && $time < $reserv['time_end'] && $reserv['status'] == 'new')
                                        $status = 'new';
                                    if ($time >= $reserv['time_start'] && $time < $reserv['time_end'] && $reserv['status'] == 'blocked')
                                        $status = 'blocked';
                                }

                            if ($status == 'reserved')
                            {
                                echo '<li class="list-group-item list-group-item-danger" time="'.$time.'"><strong>';
                                echo ' <span class="badge" style="float:right;">Забронировано</span>';
                                echo '<i class="fa fa-fw fa-clock-o"></i> '.date('H:i', $time);
                                echo '</strong></li>';
                            } elseif ($status == 'new')
                            {
                                echo '<li class="list-group-item list-group-item-warning" time="'.$time.'"><strong>';
                                echo ' <span class="badge" style="float:right;">Не подтверждено</span>';
                                echo '<i class="fa fa-fw fa-clock-o"></i> '.date('H:i', $time);
                                echo '</strong></li>';
                            } elseif ($status == 'blocked')
                            {
                                echo '<li class="list-group-item" id='.$id.' time="'.$time.'"><strong>';
                                echo ' <span class="badge" style="float:right;">Заблокировано</span>';
                                echo '<i class="fa fa-fw fa-clock-o"></i> '.date('H:i', $time);
                                echo '</strong></li>';
                            } elseif ($time < time())
                            {
                                echo '<li class="list-group-item" id='.$id.' time="'.$time.'"><strong>';
                                echo ' <span class="badge" style="float:right;"></span>';
                                echo '<i class="fa fa-fw fa-clock-o"></i> '.date('H:i', $time);
                                echo '</strong></li>';
                            }else
                            {
                                echo '<a href="" class="list-group-item list-group-item-success time" time="'.$time.'"><strong>';
                                echo ' <span class="badge" style="float:right;"></span>';
                                echo '<i class="fa fa-fw fa-clock-o"></i> '.date('H:i', $time);
                                echo '</strong></a>';
                            }
                        }
                        ?>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Выберите услугу:</h3>
        </div>

        <div class="panel-body">
            <div class="form-group">
                <?php echo CHtml::dropDownList('operation', '', CHtml::listData(Operations::model()->findAll(), 'id', 'name'), array('class' => 'form-control', 'id' => 'operations', 'style' => '', 'empty' => '')); ?>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-6" id="result">
    <?php
    if ($user_reserv['status'] == 'clear')
    {
        ?>
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title">Записаться:</h3>
            </div>
            <?php
            if (isset($error))
            {
                echo '<div class="alert alert-danger">';
                echo '<strong>Упс, как так!</strong> '.$error['time_start'][0];
                echo '</div>';
            }
            ?>
            <div class="panel-body" id="result_operation" style="font-size: 14pt;">
                <h2><em><small>Выберите услугу</small></em></h2>
            </div>
            <div class="panel-body" id="result_date" style="font-size: 14pt;">
                <h2><em><small>Выберите время</small></em></h2>
            </div> 
            <div class="panel-body" >
                <?php echo CHtml::submitButton('Подтвердить', array('name' => 'Confirm', 'id' => 'butConfirm', 'class' => 'btn btn-success', 'disabled' => 'disabled', 'style' => 'float:right;')); ?>   
            </div>
        </div>
        <?php
    } else
    {
        ?>
        <div class="<?= $user_reserv['panel_class'] ?>">
            <div class="panel-heading">
                <h3 class="panel-title"><?= $user_reserv['panel-title'] ?></h3>
            </div>
            <div class="panel-body" style="font-size: 14pt;">
                <blockquote class="text-success" style="font-size: 16pt;"><p><?= $user_reserv['name'] ?></p></blockquote>
                Примерная длительность: <strong><?= $user_reserv['duration'] ?> минут</strong><br>
                Примерная цена: <strong><?= $user_reserv['price'] ?> рублей</strong><br>
                <h3><em><small><?= $user_reserv['comment'] ?></small></em></h3>
            </div>
            <div class="panel-body" style="font-size: 14pt;">
                Когда: <strong><?= $user_reserv['date'] ?></strong><br>
                Время: <strong><?= $user_reserv['start'].' - '.$user_reserv['end'] ?></strong><br>
            </div> 
            <div class="panel-body" id="button" >
                <?php echo CHtml::link('Отменить', '', array('onclick' => 'cancel_click('.$user_reserv['id'].');', 'class' => $user_reserv['button'], 'style' => 'color:white; float:right;')); ?>   
            </div>
        </div>
    <?php } ?>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
    $(function () {
        $('#datepicker').datepicker({
            language: 'ru',
            format: 'dd-mm-yyyy',
            weekStart: 1,
        }).on('changeDate', function () {
            $.ajax({
                type: "POST",
                url: 'UpdateDate',
                cache: false,
                data: jQuery(this).parents("form").serialize(),
                success: function (html) {
                    jQuery("#date").html(html);
                }
            });
            $('#datepicker').blur();
            $('#datepicker').datepicker('hide');
        });

        $("#operations").change(function () {
            $.ajax({
                type: "POST",
                url: 'LoadOperation',
                cache: false,
                data: jQuery(this).parents("form").serialize(),
                success: function (html) {
                    jQuery("#result_operation").html(html);
                }
            });
        });

        $(".time").click(function () {
            $.ajax({
                type: "POST",
                url: 'LoadDate',
                cache: false,
                data: {'time': $(this).attr('time')},
                success: function (html) {
                    jQuery("#result_date").html(html);
                }
            });
            return false;
        });
    });

    $(document).bind("ajaxComplete", function () {
        if ($('#date_success').val() == 'true' && $('#operation_success').val() == 'true')
            $('#butConfirm').removeAttr("disabled");
        else
            $('#butConfirm').attr('disabled', 'disabled');
    });

    function cancel_click($id) {
        $.ajax({
            type: "POST",
            url: 'CancelReserv',
            cache: false,
            data: {'id': $id},
            success: function (html) {
                jQuery("#button").html(html);
            }
        });
        return false;
    }
    ;

</script>