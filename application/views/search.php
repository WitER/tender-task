<?php
$error = $success == false ? $error : false;
$row = $data;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $error ? 'Ошибка' : $row->short_name; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="<?php echo base_url(); ?>" class="btn btn-default"><i class="glyphicon glyphicon-chevron-left"></i> Назад</a>
            <h3 class="panel-title"><?php echo $error ? 'Ошибка' : $row->short_name; ?></h3>
            <span class="clearfix"></span>
        </div>
        <div class="panel-body">
            <?php if (!$error) : ?>
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">Полное название субъекта</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static"><?php echo $row->name; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">Краткое название</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static"><?php echo $row->short_name; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">ИНН</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static"><?php echo $row->inn; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">ОГРН</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static"><?php echo $row->ogrn; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">Вид предприятия</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static"><?php echo $row->category_name; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">Основной вид деятельности</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static"><?php echo $row->okved1; ?> - <?php echo $row->okved1_name; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">Дата внесения в реестр</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static"><?php echo date('d.m.Y', strtotime($row->created)); ?></p>
                    </div>
                </div>
                <?php if (!empty($row->closed)): ?>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">Дата закрытия</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static"><?php echo date('d.m.Y', strtotime($row->closed)); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($row->new) : ?>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">Новое предприятие</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static">Да</p>
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">Адрес</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static">
                            <?php echo implode(', ', array_filter([
                                    $row->region_name . ' ' . $row->region_type,
                                    $row->city_type . ' ' . $row->city,
                                    $row->street_type . ' ' . $row->street,
                                    $row->house,
                                    $row->office
                            ])); ?>
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-md-2 control-label">Дата обновления</label>
                    <div class="col-sm-12 col-md-10">
                        <p class="form-control-static"><?php echo date('d.m.Y H:i:s', strtotime($row->modified)); ?></p>
                    </div>
                </div>
            </div>
            <?php else : ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>
</html>