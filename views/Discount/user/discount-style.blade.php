<style>
label{
		font-weight: bold;
	}
	.remove_custom_open {
    position: absolute;
    cursor: pointer;
    margin-top: 10px;
  }

  .item_custom_open {
    margin-top: 10px;
  }

  select.form-control + .chosen-container.chosen-container-single .chosen-single {
    display: block;
    width: 100%;
    padding: 6px 12px;
    line-height: 1.428571429;
    color: #2f3c51;
    vertical-align: middle;
    background-color: #fff;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    background-image:none;
    font-size: .875rem;
    line-height: 17px;
    color: #2f3c51;
    border: 1px solid #e0e8ed;
}

select.form-control + .chosen-container.chosen-container-single .chosen-single div {
    top:4px;
    color:#000;
}

select.form-control + .chosen-container .chosen-drop {
    background-color: #FFF;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
    margin: 2px 0 0;

}

select.form-control + .chosen-container .chosen-search input[type=text] {
    display: block;
    width: 100%;
    padding: 6px 12px;
    line-height: 1.428571429;
    color: #2f3c51;
    vertical-align: middle;
    background-color: #FFF;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    background-image:none;
}

select.form-control + .chosen-container .chosen-results {
    margin: 2px 0 0;
    padding: 5px 0;
    list-style: none;
    background-color: #fff;
    margin-bottom: 5px;
}

select.form-control + .chosen-container .chosen-results li , 
select.form-control + .chosen-container .chosen-results li.active-result {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.428571429;
    white-space: nowrap;
    background-image:none;
}
select.form-control + .chosen-container .chosen-results li:hover, 
select.form-control + .chosen-container .chosen-results li.active-result:hover,
select.form-control + .chosen-container .chosen-results li.highlighted
{
    color: #FFF;
    text-decoration: none;
    background-color: #428BCA;
    background-image:none;
}

select.form-control + .chosen-container-multi .chosen-choices {
    display: block;
    width: 100%;
    padding: 4px;
    color: #2f3c51;
    vertical-align: middle;
    background-color: #FFF;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    background-image:none;
    font-size: .875rem;
    line-height: 17px;
    color:#2f3c51 ;
    border: 1px solid #e0e8ed;
}

select.form-control + .chosen-container-multi .chosen-choices li.search-field input[type="text"] {
    display: block;
    width: 100%;
    padding: .5rem .75rem;
    font-size: .875rem;
    line-height: 17px;
    color: #2f3c51;
    border: 1px solid #e0e8ed;
    background-color: #fff;
    background-image: none;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: .25rem;
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
}

select.form-control + .chosen-container-multi .chosen-choices li.search-choice {
    background-image: none;
    color:#2f3c51 ;
    padding: 3px 24px 3px 5px;
    margin: 2px 6px 2px 0;
    font-weight: normal;
    line-height: 1.428571429;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    border-radius: 4px;
    background-color: #FFF;
}

select.form-control + .chosen-container-multi .chosen-choices li.search-choice .search-choice-close {
    top:8px;
    right:6px;
}

select.form-control + .chosen-container-multi.chosen-container-active .chosen-choices,
select.form-control + .chosen-container.chosen-container-single.chosen-container-active .chosen-single,
select.form-control + .chosen-container .chosen-search input[type=text]:focus{
    border-color: #66AFE9;
}

select.form-control + .chosen-container-multi .chosen-results li.result-selected{
    display: list-item;
    color: #2f3c51;
    cursor: default;
    background-color: #66AFE9;
}
.upload-placeholder{
  height: auto !important;
  max-height: 500px !important;
}
    
</style>