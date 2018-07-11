<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8"/>


    <title>AcaWriter: PDF</title>

    <!-- bootstrap styles -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- fontawesome
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    -->


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <!-- customised css -->
    <link href="/css/app.css" rel="stylesheet">
    <style type="text/css">
        @font-face {
            font-family: 'fontawesome3';
            src: url('https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/fonts/fontawesome-webfont.ttf?v=4.6.1') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .fa {
            display: inline-block;
            font: normal normal normal 14px/1 fontawesome3;
            text-rendering: auto;

            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .vocab , .context, .challenge, .wordcount, .metrics, .change, .background {
            display: inline-block;
            font: normal normal normal 14px/1 fontawesome3;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding:1px;
        }


        .vocab:before {
            content: "\f05b";  /* this is your text. You can also use UTF-8 character codes as I do here */
        }

        .background:before {
            content: "\f06a";  /* this is your text. You can also use UTF-8 character codes as I do here */
            color: #721c24;
        }

        .metrics:before {
            content: "\f0e7";
            color: orangered;
        }

        .affect {
            border-bottom: 1px dashed #ff0000;
        }

        .epistemic {
            text-decoration: underline;
        }

        .link2me {
            font-weight:bold;
        }

        .modall {
            font-style:italic;
            color: darkgreen;
        }

        .challenge:before {
            content: "\f111";  /* this is your text. You can also use UTF-8 character codes as I do here */
            color:#C000C0;
        }

        .context:before {
            content: "\f0c8";  /* this is your text. You can also use UTF-8 character codes as I do here */
            color:#0000FF;

        }

        .change:before {
            content: "\f04b";  /* this is your text. You can also use UTF-8 character codes as I do here */
            color:#008300;
        }
    </style>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">




    <!-- Styles -->
</head>
<body>
<div class="row">
    <div class="col-md-12"><h2>{{$draft->name}}</h2>
        <strong>Genre: {{$draft->grammar}}&nbsp;</strong>
            Date: &nbsp;{!! $draft->created_at !!}
    </div>
</div>
<hr />

<div class="row">
    <div class="col-md-12">
        @foreach($draft->raw->rules as $rule)
            @foreach($rule->message as $msg)
                @foreach($msg as $key => $desc)
                    <span class="{{$key}}"></span>&nbsp;{!!$desc!!} <br />
                @endforeach
            @endforeach
        @endforeach
    </div>
</div>
<hr />


<div class="row">
    <div class="col-md-12">
        <strong><u>Feedback with Annotations</u></strong>
        <br />
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        {!! $draft->annotated !!}
    </div>
</div>

<br />


<div class="row">
    <div class="col-md-12">

        @foreach($draft->raw->tabs as $tab)
        @foreach($tab as $tabContent)
        @foreach($tabContent as $b)
        @foreach($b as $msg)
        @foreach($msg as $txt)
        {!! $txt !!}
        @endforeach
        @endforeach
        @endforeach
        @endforeach
        @endforeach

    </div>
</div>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.26.0/polyfill.min.js"></script>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
