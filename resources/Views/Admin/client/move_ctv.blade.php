@extends('Admin..layout_admin.master_admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			@if(session('status'))
        <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          {!! session('status') !!}
        </div>
      @endif
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2> {{trans('Admin'.DS.'client.move_ctv')}}</h2>
					<ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_dai_ly')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'client.from_daily')}}
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="daily" id="daily" class="form-control">
									@foreach($list_daily as $daily)
									<option data-avatar="{{$daily->avatar}}" value="{{$daily->id}}" {{$daily->id==old('daily')?"selected":''}}>{{$daily->full_name}}</option>
									@endforeach
								</select>
							</div>
							@if ($errors->has('daily'))
              <span style="color: red">{{ $errors->first('daily') }}</span>
              @endif
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'client.ctv')}}
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="ctv[]" id="ctv" class="form-control" multiple="">
								</select>
							</div>
							@if ($errors->has('ctv'))
              <span style="color: red">{{ $errors->first('ctv') }}</span>
              @endif
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'client.to_daily')}}
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="to_daily" id="to_daily" class="form-control">
									@foreach($list_daily as $daily)
									<option data-avatar="{{$daily->avatar}}" value="{{$daily->id}}" {{$daily->id==old('daily')?"selected":''}}>{{$daily->full_name}}</option>
									@endforeach
								</select>
							</div>
							@if ($errors->has('to_daily'))
              <span style="color: red">{{ $errors->first('to_daily') }}</span>
              @endif
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">&nbsp;
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								
								<h4>
									<input class="move_content" type="checkbox" name="move_content" {{old('move_content')?"checked":''}}> 
									<b class="text-danger">{{trans('Admin'.DS.'client.move_content')}}</b>
								</h4>

							</div>
							@if ($errors->has('move_content'))
              <span style="color: red">{{ $errors->first('move_content') }}</span>
              @endif
						</div>

						

						<div class="form-group col-md-12">
							<div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-4">
								<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'client.save')}}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
@endsection

@section('JS')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<style>
	.select2-container--default .select2-results__option[aria-disabled=true] {
		display: none;
	}
</style>
<script>
	$(function(){

		$("#daily,#to_daily").select2({
      templateResult: formatData,
      templateSelection: formatData,
      escapeMarkup: function (m) {
        return m;
      },
      minimumInputLength: -1,
      placeholder: "{{trans('Admin'.DS.'client.input_user')}}",
      language: "vi",
      closeOnSelect: true
    })



    $("#ctv").select2();


    $("#daily").on("change",function(){
			$("#to_daily option").each(function(key,elem){
				var from_daily = $("#daily").val();
				var to_daily = $(elem).attr('value');
				if(from_daily == to_daily){
					$(elem).attr('disabled',true);
				}else{
					$(elem).attr('disabled',false);
				}
				$("#to_daily").select2({
		      templateResult: formatData,
		      templateSelection: formatData,
		      escapeMarkup: function (m) {
		        return m;
		      },
		      minimumInputLength: -1,
		      placeholder: "{{trans('Admin'.DS.'client.input_user')}}",
		      language: "vi",
		      closeOnSelect: true
		    })
			})

			$.ajax({
				url : '/client/get-ctv/'+$("#daily").val(),
				type: "GET",
				success: function(res){
					$("#ctv").html(res);
					$("#ctv").select2({
			      templateResult: formatData,
			      templateSelection: formatData,
			      escapeMarkup: function (m) {
			        return m;
			      },
			      minimumInputLength: -1,
			      placeholder: "{{trans('Admin'.DS.'client.input_user')}}",
			      language: "vi",
			      closeOnSelect: true
			    })
				}
			})
		});

		// $("#to_daily").on("change",function(){
		// 	$("#daily option").each(function(key,elem){
		// 		var from_daily = $("#to_daily").val();
		// 		var to_daily = $(elem).attr('value');
		// 		if(from_daily == to_daily){
		// 			$(elem).attr('disabled',true);
		// 		}else{
		// 			$(elem).attr('disabled',false);
		// 		}
		// 		$("#daily").select2({
		//       templateResult: formatData,
		//       templateSelection: formatData,
		//       escapeMarkup: function (m) {
		//         return m;
		//       },
		//       minimumInputLength: -1,
		//       placeholder: "{{trans('Admin'.DS.'client.input_user')}}",
		//       language: "vi",
		//       closeOnSelect: true
		//     })
		// 	})
		// });
		$("#daily").trigger("change");
		setTimeout(function(){
			$("#to_daily option:nth(1)").attr('selected',true);
			$("#to_daily").trigger("change");
		},100);

    function formatData (option) {
    	var optimage = $(option.element).data('avatar'); 
      if (!option.id) { return option.text; }
      var ob = '<img width="28" height="28" src="'+ optimage +'" />&nbsp;&nbsp;&nbsp;' +  option.id + ' - ' +option.text; // replace image source with option.img (available in JSON)
      return ob;
    };
	});
</script>
@endsection