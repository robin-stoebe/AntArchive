<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 namespace app\components;
  
   use app\models\Configuration;

   use Yii;
   use yii\base\Component;
   use yii\base\InvalidConfigException;



   class Datatable extends Component
   {
       
       
        public function toArray($arr){
            $full='';

               // $full=json_encode(array_keys($arr[0]));


            foreach($arr as $row){
                    $str='';
                foreach($row as $key => $val){
                    if(!empty($str))
                        $str.=',';
                    $str.='"'.addslashes(str_replace(',',' ',$val)).'"';
                }
                if(!empty($full))
                    $full.=",";
                $full.="[".$str."]";
            }

            return "[$full]";
        }
       
       public function js($table,$class='example',$misc=''){

$js = <<< DOC
$(function(){
    $('#$class tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
   var oTable$class = $('#$class').DataTable(
                        {
                            

                            stateSave: true,
                            dom : 'frtlip',

                            $misc
                         }

                    );

        oTable$class.columns().eq( 0 ).each( function ( colIdx ) {
            if( !oTable$class.settings()[0].aoColumns[colIdx].bSearchable ){
                oTable$class.column( colIdx ).footer().innerHTML='';
        }
        oTable$class.draw();

        $( 'input', oTable$class.column( colIdx ).footer() ).on( 'keyup change', function () {
            oTable$class
                    .column( colIdx )
                    .search( this.value )
                    .draw();
            } );
        } );
   });
DOC;

            return $js;
        
       }
       public function showtable($table,$class='example',$misc=''){
           
            if(!empty($table)){
                $this->convertTable($table, $class, $width);
                return $this->js($table, $class, $misc);
                

            }

       }
       
       
      	public function convertTable($table,$class='example',$width='300px') {
		
			
			
		#			for($x=0; $x<count($table); $x++){
		#				$people[$x]=array('Select' => "<button type='button' class='ucinetselect' onclick='copyUser(\"".$people[$x]["UCINet ID"]."\",\"".$returnTo."\");return false;'>+</button>")+$people[$x] ;
		#			}
			
			
				
                    if(is_array($table[0]))
                            $colnames = array_keys($table[0]);
                    else
                            $colnames = '';

                    $thead = "<thead>\n\t<tr>\n";
                    $tfoot = "<tfoot>\n\t<tr>\n";


                    if(is_array($colnames))
                        foreach($colnames as $key)
                        {
                                if($key=='Select' and $_POST[skip]!='')
                                        continue;

                                $thead.="\t\t<th class='$key'>$key</th>\n";
                                $tfoot.="\t\t<th class='$key'>$key</th>\n";

                        }
                        $thead.="\t</tr>\n</thead>\n";
                        $tfoot.="\t</tr>\n</tfoot>\n";


                        $tbody = "<tbody>\n";

                        foreach($table as $row)
                        {

                            $row_class='';
                            $tbody .= "<tr class=''>\n";

                            foreach($row as $key=>$data)
                            {
                                    if($key=='Select' and $_POST[skip]!='')
                                                                                    continue;

                                    $tbody.="\t\t<td class='$key'>$data</td>\n";
                            }

                            $tbody .= "</tr>\n";

                        }

                        $tbody .= "</tbody>\n";


                        print   "<div  id='' class='' > 
                            <table  class='display' id='$class' style='width: $width;' >
                                $thead 
                                $tbody 
                                $tfoot
                            </table>\n
                        </div>";



                    
						
			
		
		}

   }