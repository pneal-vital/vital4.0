<!-- Beginning of fields/cedIcons.blade.php -->

{{--
    This tile will generate the Create, Edit & Delete Icons
    complete with autority checks.
    Expected arguments are;
        model => article, upc, inventory, ...
        elemType => script, th, tr, div, element type to generate
        id => ID of the $model to update/delete
--}}

@if(isset($hideCEDIcons) == false)
@if(Entrust::can([$model.'.create']) or Entrust::can([$model.'.edit']) or Entrust::can([$model.'.delete']))
    @if($elemType == 'script')
        @if(Entrust::can([$model.'.delete']))
            <script>
                $(document).ready(function() {
                    // if you come back here with errors, the form input _method is set to value="DELETE",
                    // reset it here back to PATCH
                    $("form.form-horizontal input[name=_method]").attr('value', 'PATCH');
                });

                // see: http://stackoverflow.com/questions/3915917/make-a-link-use-post-instead-of-get
                $(document).ready(function() {

                    $("a.delete").click(function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        var href = this.href;
                        //var parts = href.split('?');
                        //var url = parts[0];
                        //var params = parts[1].split('&');
                        //var pp, inputs = '';
                        //for(var i = 0, n = params.length; i < n; i++) {
                        //    pp = params[i].split('=');
                        //    inputs += '<input type="hidden" name="' + pp[0] + '" value="' + pp[1] + '" />';
                        //}
                        //$("body").append('<form action="'+url+'" method="post" id="poster">'+inputs+'</form>');
                        console.log( "list.blade.php, a.delete: " + href );
                        $("body").append('<form method="POST" id="deleter" action="'+href+'"><input name="_method" type="hidden" value="DELETE">{!! csrf_field() !!}</form>');
                        $("#deleter").submit();
                        console.log( "list.blade.php, #deleter submitted" );
                    });
                });
            </script>
        @endif
    @elseif($elemType == 'th')
        <th>{!! Lang::get('labels.icons.title') !!}</th>
    @else
        @if($elemType == 'td')
            <td>
        @elseif($elemType == 'div')
            <div class="pull-right">
        @endif

        @if(Entrust::can([$model.'.create']))
            <a href="{{URL::route($model.'.create')}}" title="{{ Lang::get('labels.icons.create') }}">{!! Html::image('img/create.png', Lang::get('labels.icons.create'),array('height'=>'20','width'=>'20')) !!}</a>
        @endif
        @if(Entrust::can([$model.'.edit']))
            &nbsp;
            <a href="{{URL::route($model.'.edit',['id' => $id])}}" title="{{ Lang::get('labels.icons.edit') }}">{!! Html::image('img/edit.jpeg', Lang::get('labels.icons.edit'),array('height'=>'20','width'=>'20')) !!}</a>
        @endif
        @if(Entrust::can([$model.'.delete']))
            &nbsp; &nbsp; &nbsp;
            <a class="delete" href="{{URL::route($model.'.destroy',['id' => $id])}}" title="{{ Lang::get('labels.icons.delete') }}">{!! Html::image('img/delete.png', Lang::get('labels.icons.delete'),array('height'=>'20','width'=>'20')) !!}</a>
        @endif

        @if($elemType == 'div')
            </div>
        @elseif($elemType == 'td')
            </td>
        @endif
    @endif
@endif
@endif

<!-- End of fields/cedIcons.blade.php -->
