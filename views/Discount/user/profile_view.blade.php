<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.profile'))}}</h3>
	<div class="form-edit-profile">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.full_name')}}</label>
					<p>{{$user->full_name?$user->full_name:''}}</p>
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.email')}}</label>
					<p>{{$user->email?$user->email:''}}</p>
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.birthday')}}</label>
					<p>{{$user->birthday?date('d-m-Y',strtotime($user->birthday)):''}}</p>
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.phone')}}</label>
					<p>{{$user->phone?$user->phone:''}}</p>
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.address')}}</label>
					<p>{{$user->address?$user->address:''}}</p>
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.description_user')}}</label>
					<p>
						{{$user->description?$user->description:''}}
					</p>
				</div>
				<!-- end form-group -->
			</div>
		</div>
	</div>
</div>