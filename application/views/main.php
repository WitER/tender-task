<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Тестовое задание</title>

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
            <h3 class="panel-title">Проверить является ОГРН(ИП) или ИНН субъектом малого или среднего предпринимательства</h3>
        </div>
        <div class="panel-body">
            <div class="preload text-center hidden">
                <i class="glyphicon glyphicon-refresh" style="font-size: 30px;"></i>
                <p>Выполнение запроса</p>
            </div>
            <?php echo form_open('', 'class="form-horizontal searchForm"'); ?>
                <div class="form-group inputGroupQuery">
                    <div class="input-group ">
                        <div class="input-group-addon innOrOgrn">ИНН \ ОГРН</div>
                        <input type="text" class="form-control" id="inputQuery" name="search" placeholder="Введите ИНН или ОГРН(ИП)">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">Найти</button>
                        </span>
                    </div>
                    <span id="inputQueryHelp" class="help-block"></span>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-info searchResult hidden">
        <div class="panel-heading">
            <h3 class="panel-title"></h3>
        </div>
        <div class="panel-body">
            <div class="success">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-12 col-md-2 control-label">Полное название субъекта</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static fullName"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-md-2 control-label">Краткое название</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static shortName"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-md-2 control-label">ИНН</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static inn"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-md-2 control-label">ОГРН</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static ogrn"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-md-2 control-label">Вид предприятия</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static categoryName"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-md-2 control-label">Основной вид деятельности</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static okved"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-md-2 control-label">Дата внесения в реестр</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static created"></p>
                        </div>
                    </div>
                    <div class="form-group isClosed">
                        <label class="col-sm-12 col-md-2 control-label">Дата закрытия</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static closed"></p>
                        </div>
                    </div>
                    <div class="form-group isNew">
                        <label class="col-sm-12 col-md-2 control-label">Новое предприятие</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static">Да</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-md-2 control-label">Адрес</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static address"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-md-2 control-label">Дата обновления</label>
                        <div class="col-sm-12 col-md-10">
                            <p class="form-control-static modified"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Последние найденные субъекты</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Дата обновления</th>
                        <th>ИНН</th>
                        <th>ОГРН</th>
                        <th>Название</th>
                        <th>Тип</th>
                    </tr>
                    </thead>
                    <tbody class="lastResults">
                    <?php foreach ($data as $row) : ?>
                    <tr>
                        <td><?php echo date('d.m.Y H:i:s', strtotime($row->modified)); ?></td>
                        <td><a href="<?php echo base_url($row->inn); ?>"><?php echo $row->inn; ?></a></td>
                        <td><?php echo $row->ogrn; ?></td>
                        <td><?php echo $row->name; ?></td>
                        <td><?php echo $row->category_name; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" crossorigin="anonymous"></script>
<script src="<?php echo base_url('assets/main.js'); ?>"></script>

</body>
</html>