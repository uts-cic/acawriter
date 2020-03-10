<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8"/>
    <title>AcaWriter: PDF</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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

        /* AI/2019-06-25: Removing affect analysis
        .affect {
            border-bottom: 1px dashed #ff0000;
        }*/

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
</head>
<body>
    <div class="row">
        <div class="col-md-12">
            <h2>{{ $draft->name }}</h2>
            <strong>Genre: {{ $draft->grammar }}&nbsp;</strong>
            Date: &nbsp;{!! $draft->created_at !!}
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-md-12">
            @foreach ($draft->raw->rules as $rule)
                @if (!isset($rule->tab))
                    @if (isset($rule->custom) )
                        <strong>{!! $rule->custom !!}</strong><br />
                    @endif
                    @foreach ($rule->message as $msg)
                        @foreach ($msg as $key => $desc)
                            <span class="{{ $key }}"></span><p>{!! $desc !!}</p>
                        @endforeach
                    @endforeach
                @elseif ($rule->tab == 1)
                    @if (isset($rule->custom) )
                        <strong>{!! $rule->custom !!}</strong><br />
                    @endif
                    @foreach ($rule->message as $msg)
                        @foreach ($msg as $key => $desc)
                            @if (in_array($key, array('context', 'change', 'challenge')))
                                <span class="{{ $key }}"></span> {!! $desc !!} <br />
                            @else
                                <p class="{{ $rule->name }}">{!! $desc !!}</p>
                            @endif
                        @endforeach
                    @endforeach
                @endif
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
            <strong><u>Feedback about your writing</u></strong>
            <br />
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if (isset($draft->raw->tabs))
                @foreach ($draft->raw->tabs as $tab)
                    @foreach ($tab as $tabContent)
                        @foreach ($tabContent as $name => $b)
                            @if ($name !== 'faq')
                                @foreach ($b as $msg)
                                    @foreach ($msg as $txt)
                                        <p>{!! $txt !!}</p>
                                    @endforeach
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            @endif
        </div>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/6.26.0/polyfill.min.js"></script>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
