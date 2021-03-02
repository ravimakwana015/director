<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="{{ asset('public/admin/images/user2-160x160.png')  }}" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p>{{ Auth::user()->name }}</p>
			</div>
		</div>

		<!-- /.search form -->
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MAIN NAVIGATION</li>
			<li>
				<a href="{{ route('admin.home') }}">
					<i class="fa fa-dashboard"></i> <span>Dashboard</span>
				</a>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>User Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('users.index') }}"><i class="fa fa-circle-o"></i>Manage Users</a>
					</li>
                    <li>
						<a href="{{ route('user.deleted') }}"><i class="fa fa-circle-o"></i>Deleted Users</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-tasks"></i><span>Subscription Plans</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('plans.index') }}"><i class="fa fa-circle-o"></i>Subscription Plans</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Careers Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('careers.index') }}"><i class="fa fa-circle-o"></i>Manage Careers</a>
					</li>
					<li>
						<a href="{{ route('careersrequest.index') }}"><i class="fa fa-circle-o"></i>Careers Request</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Enter Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('discover.index') }}"><i class="fa fa-circle-o"></i>Manage Enter</a>
					</li>
					<li>
						<a href="{{ route('discoverrequest.index') }}"><i class="fa fa-circle-o"></i>Enter Request</a>
					</li>
					<li>
						<a href="{{ route('discoverlikes.index') }}"><i class="fa fa-circle-o"></i>Enter Likes</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Develop Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('explore.index') }}"><i class="fa fa-circle-o"></i>Manage Develop</a>
					</li>
					<li>
						<a href="{{ route('explorerequest.index') }}"><i class="fa fa-circle-o"></i>Develop Request</a>
					</li>
					{{-- <li>
						<a href="{{ route('careersrequest.index') }}"><i class="fa fa-circle-o"></i>Careers Request</a>
					</li> --}}
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Transaction Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('transaction.data') }}"><i class="fa fa-circle-o"></i>Manage Transaction</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Sliders Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('sliders.index') }}"><i class="fa fa-circle-o"></i>Manage Sliders</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Community Forum</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('forumcategory.index') }}"><i class="fa fa-circle-o"></i>Manage Category</a>
					</li>
					<li>
						<a href="{{ route('topic.index') }}"><i class="fa fa-circle-o"></i>Topics</a>
					</li>
					<li>
						<a href="{{ route('comment.index') }}"><i class="fa fa-circle-o"></i>Comments</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Refer User Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					{{-- <li>
						<a href="{{ route('refercode.index') }}"><i class="fa fa-circle-o"></i>Generate Refer Code</a>
					</li> --}}
					<li>
						<a href="{{ route('referlist.index') }}"><i class="fa fa-circle-o"></i> Refer List</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-envelope"></i>  <span>Contact Inquire</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					{{-- <li>
						<a href="{{ route('refercode.index') }}"><i class="fa fa-circle-o"></i>Generate Refer Code</a>
					</li> --}}
					<li>
						<a href="{{ route('contactinquire.index') }}"><i class="fa fa-circle-o"></i> Contact Inquiry List</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-exclamation-triangle"></i>  <span>Chat Report</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('chatreport.index') }}"><i class="fa fa-circle-o"></i> Chat Report</a>
					</li>
				</ul>
			</li>
            <li class="treeview">
				<a href="#">
					<i class="fa fa-plus-circle"></i>  <span>Increase User Points</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('increase.user.likes.form') }}"><i class="fa fa-circle-o"></i> Increase User Points</a>
					</li>
                    <li>
						<a href="{{ route('increase.user.personality.form') }}"><i class="fa fa-circle-o"></i> Increase User Personality Traits</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Pages</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('pages.index') }}"><i class="fa fa-circle-o"></i>Pages</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>Reports</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('user.reports') }}"><i class="fa fa-circle-o"></i>User Report</a>
					</li>
					<li>
						<a href="{{ route('transaction.reports') }}"><i class="fa fa-circle-o"></i>Transaction Report</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>SEO</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('general.edit','1') }}"><i class="fa fa-circle-o"></i>General SEO</a>
					</li>
					<li>
						<a href="{{ route('careerlist.edit','1') }}"><i class="fa fa-circle-o"></i>Career List SEO</a>
					</li>
					<li>
						<a href="{{ route('careerpage.edit','1') }}"><i class="fa fa-circle-o"></i>Career Details Page SEO</a>
					</li>
					<li>
						<a href="{{ route('profilelist.edit','1') }}"><i class="fa fa-circle-o"></i>Profile List Page SEO</a>
					</li>
					<li>
						<a href="{{ route('profilepage.edit','1') }}"><i class="fa fa-circle-o"></i>Profile Details Page SEO</a>
					</li>
					<li>
						<a href="{{ route('discoverlist.edit','1') }}"><i class="fa fa-circle-o"></i>Enter List SEO</a>
					</li>
					<li>
						<a href="{{ route('discoverpage.edit','1') }}"><i class="fa fa-circle-o"></i>Enter Details Page SEO</a>
					</li>
					<li>
						<a href="{{ route('explorelist.edit','1') }}"><i class="fa fa-circle-o"></i>Develop List SEO</a>
					</li>
					<li>
						<a href="{{ route('explorepage.edit','1') }}"><i class="fa fa-circle-o"></i>Develop Details Page SEO</a>
					</li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-flag"></i> <span>Country</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('country.index') }}"><i class="fa fa-circle-o"></i>Country List</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="{{ route('contact.index') }}">
					<i class="fa fa-gear"></i> <span>Contact & Help</span>
				</a>
			</li>
            <li>
				<a href="{{ route('access-code.index') }}">
					<i class="fa fa-gear"></i> <span>Access Code</span>
				</a>
			</li>
            <li>
				<a href="{{ route('settings.edit','1') }}">
					<i class="fa fa-gear"></i> <span>Settings</span>
				</a>
			</li>
			{{-- <li class="treeview">
				<a href="#">
					<i class="fa fa-users"></i> <span>User Invitation Link</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="{{ route('user-invitation.index') }}"><i class="fa fa-circle-o"></i>Invitation List</a>
					</li>
				</ul>
			</li> --}}
		</ul>
	</section>
</aside>
