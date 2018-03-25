function checkInn(i)
{
    if ( i.match(/\D/) ) return false;

    var inn = i.match(/(\d)/g);

    if ( inn.length == 10 )
    {
        return inn[9] == String(((
            2*inn[0] + 4*inn[1] + 10*inn[2] +
            3*inn[3] + 5*inn[4] +  9*inn[5] +
            4*inn[6] + 6*inn[7] +  8*inn[8]
        ) % 11) % 10);
    }
    else if ( inn.length == 12 )
    {
        return inn[10] == String(((
            7*inn[0] + 2*inn[1] + 4*inn[2] +
            10*inn[3] + 3*inn[4] + 5*inn[5] +
            9*inn[6] + 4*inn[7] + 6*inn[8] +
            8*inn[9]
        ) % 11) % 10) && inn[11] == String(((
            3*inn[0] +  7*inn[1] + 2*inn[2] +
            4*inn[3] + 10*inn[4] + 3*inn[5] +
            5*inn[6] +  9*inn[7] + 4*inn[8] +
            6*inn[9] +  8*inn[10]
        ) % 11) % 10);
    }

    return false;
}

function checkOgrn(chekedValue) {
    //дальше работаем со строкой
    chekedValue += '';

    //для ОГРН в 13 знаков
    if(chekedValue.length == 13 &&
        (chekedValue.slice(12,13) == ((chekedValue.slice(0,-1))%11 + '').slice(-1))){
        return true;

        //для ОГРН в 15 знаков
    }else if(chekedValue.length == 15 &&
        (chekedValue.slice(14,15) == ((chekedValue.slice(0,-1))%13 + '').slice(-1))){
        return true;

    }else{
        return false;
    }
}

function updateLast() {
    $.get(
        '/json',
        function (result) {
            if (result.success) {
                result = result.data;
                let $root = $('.lastResults');
                $root.empty();
                $.each(result, function (i, v) {

                    let $modified = new moment(v.modified);
                    $root.append(
                        '<tr>' +
                        '<td>' + ($modified.format('DD.MM.YYYY HH:mm:ss')) + '</td>' +
                        '<td><a href="/' + v.inn  + '">' + v.inn + '</a></td>' +
                        '<td>' + v.ogrn + '</td>' +
                        '<td>' + v.name + '</td>' +
                        '<td>' + v.category_name + '</td>'
                    );
                });
            }
        },
        'json'
    );
}

$(document).ready(function () {
    $('#inputQuery').on('keyup', function () {
        let $val = $(this).val();
        $('.inputGroupQuery').removeClass('has-success has-error has-warning');

       let isInn = $val != '' ? checkInn($val) : false;
       let isOgrn = $val != '' ? checkOgrn($val) : false;

       if (!isInn && !isOgrn) {
           $('.inputGroupQuery').addClass('has-warning');
           $('.innOrOgrn').text('ИНН / ОГРН');
           $('#inputQueryHelp').text('Укажите верный ИНН или ОГРН(ИП)');
           return;
       }

       if (isInn) {
           $('.inputGroupQuery').addClass('has-success');
           $('.innOrOgrn').text('ИНН');
           $('#inputQueryHelp').text('');
           return;
       }

        if (isOgrn) {
            $('.inputGroupQuery').addClass('has-success');
            $('.innOrOgrn').text('ОГРН(ИП)');
            $('#inputQueryHelp').text('');
            return;
        }
    });

    $('.searchForm').on('submit', function (e) {
        e.preventDefault();
        let $form = $(this);
        let $root = $('.searchResult');
        $root.addClass('hidden');
        $('.form-group', $form).addClass('hidden');
        $('.preload', $form).removeClass('hidden');
        $.post(
            '/json',
            $(this).serializeArray(),
            function (result)
            {
                if (result.success == false && result.error != null) {
                    $('.inputGroupQuery').addClass('has-error');
                    $('.innOrOgrn').text('ИНН / ОГРН');
                    $('#inputQueryHelp').text(result.error);
                } else {
                    result = result.data;
                    $('.panel-title', $root).text(result.name);
                    $('.fullName', $root).text(result.name);
                    $('.shortName', $root).text(result.short_name);
                    $('.inn', $root).text(result.inn);
                    $('.ogrn', $root).text(result.ogrn);
                    $('.categoryName', $root).text(result.category_name);
                    $('.okved', $root).text(result.okved1 + ' : ' + result.okved1_name);

                    let $created = new moment(result.created);
                    $('.created', $root).text($created.format('DD.MM.YYYY'));
                    if (result.closed != null) {
                        let $closed = new moment(result.closed);
                        $('.closed', $root).text($closed.format('DD.MM.YYYY'));
                        $('.isClosed', $root).removeClass('hidden');
                    } else {
                        $('.isClosed', $root).addClass('hidden');
                    }
                    if (result.new) {
                        $('.isNew', $root).removeClass('hidden');
                    } else {
                        $('.isNew', $root).addClass('hidden');
                    }
                    $('.address', $root).text(
                        [
                            result.region_name + ' ' + result.region_type,
                            result.city_type + ' ' + result.city,
                            result.street_type + ' ' + result.street,
                            result.house,
                            result.office
                        ].join(', ')
                    );
                    let $modified = new moment(result.modified);
                    $('.modified', $root).text($modified.format('DD.MM.YYYY HH:mm:ss'));
                    $root.removeClass('hidden');

                    updateLast();
                }
                $('.preload', $form).addClass('hidden');
                $('.form-group', $form).removeClass('hidden');

            },
            'json'
        );
        return false;
    })
});