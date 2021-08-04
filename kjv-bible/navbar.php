<nav class="navbar navbar-expand-md navbar-dark  bg-info fixed-top">
    <a href="#" class="navbar-brand "><img src="imgs/open-bible.png"  class="img" style="height:90px" /> &nbsp;  <span class="h4 bold  mt-2 pt-2">Holy Bible </span> </a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse"> <!--  -->
          <div class="navbar-nav">           
			<a href="../only-believe-hymns/" title="View Only Believe Hymns " class="nav-item nav-link active ml-3 pl-3 btn btn-info"> Only Believe Hymns &nbsp; <i class="fa fa-list"></i> </a>
		   
			 <a href="#" title="Create Notes for the sermon" class="nav-item nav-link active ml-3 pl-3 btn btn-info" onclick="get_bible_passage('.bible_ref')" data-toggle="modal" data-target="#_notes"> Message Notes   &nbsp;<i class="fa fa-edit"></i></a>
			 <!-- 
			<a href="#" class="nav-item nav-link">Profile</a>
            <a href="#" class="nav-item nav-link">Messages</a>
            <a href="#" class="nav-item nav-link disabled" tabindex="-1">Reports</a> -->
        </div> 
		<form class="form-inline ml-auto">
			<button title="Save The Bible Reference To Message Note" type="button" onclick="save_bible_passage()" class="btn btn-icons btn-dark "><i class="fa fa-save"></i></button>&nbsp;
            <input type="text" class="form-control text-search mr-sm-2" style="font-size:24px;" placeholder="Read / Search Bible ">
            <button type="submit" class="btn btn-warning read-book btn-lg "> Read &nbsp;<i class="fa fa-book"></i></button> &nbsp; 
            <button type="submit" class="btn btn-dark search-book btn-lg "> Search &nbsp;<i class="fa fa-search"></i></button>
        </form>
        <div class="navbar-nav ml-auto">
            <a href="#" class="nav-item nav-link"> <i class="fa fa-cog fa-2x text-white"  data-toggle="modal" data-target="#_settings"></i></a>
        </div>
    </div>
</nav>