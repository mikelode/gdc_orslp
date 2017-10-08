<html>
    <head>
        <link href="{{ asset('/plugins/autocom/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
        <script src="{{ asset('/plugins/autocom/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('/plugins/autocom/jquery-ui/jquery-ui.js') }}"></script>
    </head>
    <body>
           <form method="get">
                <div class="form-group">
                    <input type="text" class="form-control input-sm" name="auto" id="auto" >
                </div>
                <div class="form-group">
                    <input type="text" class="form-control input-sm" name="response" id="response" disabled>
                </div>
            </form>


       <script>

       $('#auto').autocomplete({
           source: 'getdata',
           minLength: 1,
           select:function(e,ui){
            console.log('Mostrasr');
               //$('#response').val(ui[0].value);
           }
       });

        /*
       $('.search').keyup(function(e){
            $.ajax({
                url: 'getdata?s='+e.target.value
            }).success(function(data){
                $('.searchlist').show(300);
                $('.searchlist ul').empty();
                $.each(data,function(i, prd){
                    $('.searchlist ul').append('<li>'+prd.title+'</li>');
                });
            });
        });
        $('.searchlist').on('click', 'li', function(){
            $('input').val($(this).text());
            $('.searchlist').hide(300);
        });
        */


       </script>
<!--
    <h2 class="">Autocomplete</h2>
    <div class="row">
        <div class="col-lg-12 form-group">
            <div class="col-lg-12">
                <input type="text" id="autocomplete" class="form-control">
            </div>
        </div>
    </div>

    <script>

        var availableTags = [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
        ];

        $('input:text').bind({

        });

        $( "#response" ).autocomplete({
            source: 'getdata',
            minLength: 1

        });
    </script>-->



    </body>
</html>