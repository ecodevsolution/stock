<?php
    $this->registerJs('
        $(document).ready(function(){
            var myVar;
            $( "#income" ).click(function() {
                myFunction(this);
            });
             $( "#yearly" ).click(function() {
                myFunction(this);
            });
             $( "#profit" ).click(function() {
                myFunction(this);
            }); 
             function myFunction(div) {
                $(".loader").toggle();
                $(div).toggle();

            }                
        });
    ');
?>


<a id="income" href="?r=report/index">
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="content">
              <div class="dashboard_v4_box_icon float-xs-left success_box">
                  <i class="fa fa-line-chart"></i>
              </div>
              <div class="dashboard_v4_box_title float-xs-right">
                  <h4>Total Income</h4>
                  <p>Review your income by range period</p>
              </div>
          </div>
      </div>
  </a>
  <a id="yearly" href="?r=report/sell">
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="content">
              <div class="dashboard_v4_box_icon float-xs-left warning_box">
                  <i class="fa fa-pie-chart"></i>
              </div>
              <div class="dashboard_v4_box_title float-xs-right">
                  <h4>Yearly Sell</h4>
                  <p>Review your selling by Year Period</p>
              </div>
          </div>
      </div>
  </a>
  <a id="profit" href="?r=report/profit">
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="content">
              <div class="dashboard_v4_box_icon float-xs-left danger_box">
                  <i class="fa fa-bar-chart"></i>
              </div>
              <div class="dashboard_v4_box_title float-xs-right">
                  <h4>Profit / Loss</h4>
                  <p>Review Profit or Loss </p>
              </div>
          </div>
      </div>
  </a>