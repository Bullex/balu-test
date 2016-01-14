<html>
    <head>
        <title>Nested set</title>
        <!-- Bootstrap CSS served from a CDN -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
                type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('css/base.css') }}" />
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>

        <!-- Modal -->
        <div id="modal" class="modal fade" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <span id="header_text"></span>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <!-- Form itself -->
                        <form name="createNode" class="form" id="nodeForm" novalidate>
                            <input type="hidden" name="parent_id" value=""/>
                            <div class="control-group">
                                <div class="controls">
                                    <input type="text" name="name" class="form-control"
                                            placeholder="Node name" id="name" required/>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-lg btn-3d pull-right">Create</button><br />
                        </form>
                    </div><!-- End of Modal body -->
                </div><!-- End of Modal content -->
            </div><!-- End of Modal dialog -->
        </div><!-- End of Modal -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
    </body>
</html>
