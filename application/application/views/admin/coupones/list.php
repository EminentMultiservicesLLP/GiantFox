<!DOCTYPE html>
<html lang="en">
   <head>
      <title><?php echo $page_title; ?> List</title>
       <?php $this->load->view('admin/include/allcss') ?>

   </head>
   <body>
      <div id="snackbar"><?php echo $this->session->flashdata('message'); ?></div>
     <?php if($this->session->flashdata('message')){ ?>
       <script>
         function myFunction() {
           var x = document.getElementById("snackbar");
           x.className = "show";
           setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
         }
         myFunction();
         </script>
  
   <?php } ?>
      <?php echo loder; ?>
      <!--  -->
      <div id="app" class="app app-header-fixed app-sidebar-fixed ">


        
          <?php $this->load->view('admin/include/header') ?>
          <?php $this->load->view('admin/include/sidebar') ?>


         
         <div id="content" class="app-content">
           
            <h1 class="page-header"><?php echo $page_title; ?> List</h1>

           

            <div class="row">
               
               <div class="col-xl-12">
                  <div class="panel panel-inverse">
                     <div class="panel-heading" style="justify-content: space-between;">
                        <h4 class="panel-title">All <?php echo $page_title; ?></h4>
                        <a href="<?php echo $add_url; ?>" class="btn btn-primary" style="margin-right: 10px;">Add New <?php echo $page_title; ?></a>
                        <button type="button" onclick="delete_all()" class="btn btn-danger">Delete Multiple</button>
                     </div>

                     <div class="panel-body">
                        <table id="data-table-buttons" class="table table-striped table-bordered align-middle">
                           <thead>
                              <tr>
                                 <th width="1%">#</th>
                                 <th>Name</th>
                                 <th>Amount</th>
                                 <th>Type</th>
                                 <th class="text-nowrap">Status</th>
                                 <th class="text-nowrap">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $i=0;
                              foreach ($ALLDATA as $key => $data) 
                                 {  $i++;   ?>
                              <tr class="odd gradeX">
                                 <td width="1%" class="fw-bold text-dark">
                                    <label>
                                       <?php echo $i; ?>
                                       <input type="checkbox" name="multiple_delete[]" value="<?php echo $data->id; ?>" class="multiple_delete">
                                    </label>
                                 </td>
                                 
                                 <td><?=$data->name ?></td>
                                 <td><?=number_format($data->amount,2) ?></td>
                                 <td><?php echo coupontype($data->type); ?></td>
                                 
                                 <td class="">
                                    <span id="statusbyid<?=$data->id ?>"><?php echo status($data->status); ?> </span>
                                    <label class="switch" for="customSwitch-<?=$data->id ?>">
                                      <input type="checkbox" id="customSwitch-<?=$data->id ?>" <?php if($data->status==1)echo'checked'; ?> onclick="click_here(<?=$data->id ?>)">
                                      <span class="slider round"></span>
                                    </label>
                                 </td>

                                 <td>
                                    <!-- <a href="<?php echo $view_url.$data->id; ?>" class="btn btn-primary btn-sm">View</a> -->
                                    <a href="<?php echo $edit_url.$data->id; ?>" class="btn btn-info btn-sm">Edit</a>
                                    <a href="<?php echo $delete_url.$data->id; ?>" class="btn btn-danger btn-sm delete">Delete</a>
                                 </td>
                              </tr>
                           <?php } ?>
                              
                            
                           </tbody>
                        </table>
                     </div>
                    
                  </div>
               </div>
            </div>
         </div>
       
      </div>



   <?php $this->load->view('admin/include/footer') ?>

  <script type="text/javascript">
        function click_here(id)
        {
            current_element = $('#statusbyid'+id);
            if($('#customSwitch-'+id).prop("checked")==true)
                var status = 1;
            else
                var status = 0;
            $.ajax({
                url:'<?php echo $status_value; ?>',
                method:'post',
                data:{status:status,id:id},
                success:function(data){
                    console.log(data);
                    // location.reload();
                    var result = JSON.parse(data);
                    current_element.html(result.data['status']);
                }
            });
        }

        /*-------delete all--------*/

        function delete_all()
         {
            var ids = [];
            $('.multiple_delete:checked').each(function () {
                ids.push(this.value);
            });

            if(ids.length==0)
            {
               swal("Atleast select one ....");
               return false;
            }

             $.ajax({
               url:'<?php echo $multiple_delete; ?>',
               method:'post',
               data:{id:ids},
               success:function(data)
               {                  
                  swal({title: "Delete Successfully Done....", text: "", type: 
                    "success"}).then(function(){ 
                       location.reload();
                       }
                    );              
                  console.log(data);
               }
               });
         }
   </script>

   </body>
</html>