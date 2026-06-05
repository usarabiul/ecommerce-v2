<table class="table mt-1">
     <thead>
         <tr>
             <th>Module Permission </th>
             <th><small>Items Added & Updated</small> </th>
             <th><small>Items Deleted</small> </th>
             <th><small> All Users Items Permited </small></th>
             <th>
             	<label>
             	<input type="checkbox"  id="checkall" style="display: inline-block;">
             	<small> All (Leftside Show Menus) </small>
             	</label>
           		</th>
         </tr>
     </thead>
     <tbody>
         <tr style="background: #f3f3f3;">
             <td>Posts </td>
             <td>
             	<label>
                 	<input type="checkbox" name="permission[posts][add]" @isset(json_decode($role->permission, true)['posts']['add']) checked @endisset> Add/Update</label>
             </td>
             <td>
                 <label>
                 	<input type="checkbox" name="permission[posts][delete]" @isset(json_decode($role->permission, true)['posts']['delete']) checked @endisset> Delete</label>
             </td>
             <td>
                <label>
                 	<input type="checkbox" name="permission[posts][all]"  @isset(json_decode($role->permission, true)['posts']['all']) checked @endisset> All</label>
             </td>
             <td>
                 <label>
                 	<input type="checkbox" name="permission[posts][list]" @isset(json_decode($role->permission, true)['posts']['list']) checked @endisset> List</label>
             </td>
         </tr>
          <tr style="background: #f3f3f3;">
             <td>Posts Others </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[postsOther][category]" @isset(json_decode($role->permission, true)['postsOther']['category']) checked @endisset> Categories</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[postsOther][tags]"  @isset(json_decode($role->permission, true)['postsOther']['tags']) checked @endisset> Tags</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[postsOther][comments]" @isset(json_decode($role->permission, true)['postsOther']['comments']) checked @endisset> Comments</label>
             </td>
             <td>
                 
             </td>
         </tr>
         
          <tr style="background: #57f7e8;">
             <td>Pages </td>
             <td>
             	<label>
                 	<input  type="checkbox" name="permission[pages][add]" @isset(json_decode($role->permission, true)['pages']['add']) checked @endisset> Add/Update</label>
             </td>
             <td>
                 <label>
                 	<input  type="checkbox" name="permission[pages][delete]" @isset(json_decode($role->permission, true)['pages']['delete']) checked @endisset> Delete</label>
             </td>
             <td>
                <label>
                 	<input  type="checkbox" name="permission[pages][all]" @isset(json_decode($role->permission, true)['pages']['all']) checked @endisset> All</label>
             </td>
             <td>
                 <label>
                 	<input type="checkbox" name="permission[pages][list]" @isset(json_decode($role->permission, true)['pages']['list']) checked @endisset> List</label>
             </td>
         </tr>
         
         <tr >
             <td>Medies Library </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[medies][add]" @isset(json_decode($role->permission, true)['medies']['add']) checked @endisset> Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[medies][delete]" @isset(json_decode($role->permission, true)['medies']['delete']) checked @endisset> Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[medies][all]" @isset(json_decode($role->permission, true)['medies']['all']) checked @endisset> All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[medies][list]" @isset(json_decode($role->permission, true)['medies']['list']) checked @endisset> List</label>
             </td>
         </tr>


         <tr style="background: #edd0d0;">
             <td>Services</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[services][add]" @isset(json_decode($role->permission, true)['services']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[services][delete]" @isset(json_decode($role->permission, true)['services']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[services][all]" @isset(json_decode($role->permission, true)['services']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[services][list]" @isset(json_decode($role->permission, true)['services']['list']) checked @endisset > List</label>
             </td>
         </tr>
         
         <tr style="background: #edd0d0;">
             <td>Services Others</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[servicesOthers][category]" @isset(json_decode($role->permission, true)['servicesOthers']['category']) checked @endisset > Categories</label>
             </td>
             <td>
                
             </td>
             <td>
                
             </td>
             <td>
                 
             </td>
        </tr>

         <tr style="background: #f5b8ff;">
             <td>Clients</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[clients][add]" @isset(json_decode($role->permission, true)['clients']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[clients][delete]" @isset(json_decode($role->permission, true)['clients']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[clients][all]" @isset(json_decode($role->permission, true)['clients']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[clients][list]" @isset(json_decode($role->permission, true)['clients']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr style="background: #f5b8ff;">
             <td>Brands</td>
             <td>
                <label>
                    <input  type="checkbox"  name="permission[brands][add]" @isset(json_decode($role->permission, true)['brands']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[brands][delete]" @isset(json_decode($role->permission, true)['brands']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[brands][all]" @isset(json_decode($role->permission, true)['brands']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[brands][list]" @isset(json_decode($role->permission, true)['brands']['list']) checked @endisset > List</label>
             </td>
         </tr>

          <tr style="background: #f5b8ff;">
             <td>Sliders</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[sliders][add]" @isset(json_decode($role->permission, true)['sliders']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[sliders][delete]" @isset(json_decode($role->permission, true)['sliders']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[sliders][all]" @isset(json_decode($role->permission, true)['sliders']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[sliders][list]" @isset(json_decode($role->permission, true)['sliders']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr style="background: #f5b8ff;">
             <td>Galleries</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[galleries][add]" @isset(json_decode($role->permission, true)['galleries']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[galleries][delete]" @isset(json_decode($role->permission, true)['galleries']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[galleries][all]" @isset(json_decode($role->permission, true)['galleries']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[galleries][list]" @isset(json_decode($role->permission, true)['galleries']['list']) checked @endisset > List</label>
             </td>
         </tr>

          <tr style="background: #f5b8ff;">
             <td>Menus Setting</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[menus][add]" @isset(json_decode($role->permission, true)['menus']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[menus][delete]" @isset(json_decode($role->permission, true)['menus']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[menus][all]" @isset(json_decode($role->permission, true)['menus']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[menus][list]" @isset(json_decode($role->permission, true)['menus']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr>
             <td>Theme Setting</td>
             <td>
                <label>
                    
             </td>
             <td>
                 
             </td>
             <td>
                
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[themeSetting][list]" @isset(json_decode($role->permission, true)['themeSetting']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr style="background: #dadfff;">
             <td>Administrator Users</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[adminUsers][add]" @isset(json_decode($role->permission, true)['adminUsers']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[adminUsers][delete]" @isset(json_decode($role->permission, true)['adminUsers']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[adminUsers][list]" @isset(json_decode($role->permission, true)['adminUsers']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr style="background: #dadfff;">
             <td>Roles User</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[adminRoles][add]" @isset(json_decode($role->permission, true)['adminRoles']['add']) checked @endisset > Add/Update</label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[adminRoles][delete]" @isset(json_decode($role->permission, true)['adminRoles']['delete']) checked @endisset > Delete</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[adminRoles][all]" @isset(json_decode($role->permission, true)['adminRoles']['all']) checked @endisset > All</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[adminRoles][list]" @isset(json_decode($role->permission, true)['adminRoles']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr style="background: #dadfff;">
             <td>Customer Users</td>
             <td>
                <label>
                    <input type="checkbox" name="permission[users][add]" @isset(json_decode($role->permission, true)['users']['add']) checked @endisset > Add</label>
             </td>
             <td>
                <label>
                    <input type="checkbox" name="permission[users][update]" @isset(json_decode($role->permission, true)['users']['update']) checked @endisset > Update</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[users][delete]" @isset(json_decode($role->permission, true)['users']['delete']) checked @endisset > Delete</label>
             </td>
             
             <td>
                 <label>
                    <input type="checkbox" name="permission[users][list]" @isset(json_decode($role->permission, true)['users']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr style="background: #dadfff;">
             <td>Subscribe Users</td>
             <td>

             </td>
             <td>

             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[subscribe][delete]" @isset(json_decode($role->permission, true)['subscribe']['delete']) checked @endisset > Delete</label>
             </td>
             
             <td>
                 <label>
                    <input type="checkbox" name="permission[subscribe][list]" @isset(json_decode($role->permission, true)['subscribe']['list']) checked @endisset > List</label>
             </td>
         </tr>

         <tr >
             <td>Apps Setting</td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[appsSetting][general]" @isset(json_decode($role->permission, true)['appsSetting']['general']) checked @endisset > General Setting </label>
             </td>
             <td>
                 <label>
                    <input  type="checkbox" name="permission[appsSetting][mail]" @isset(json_decode($role->permission, true)['appsSetting']['mail']) checked @endisset > Mail Setting</label>
             </td>
             <td>
                <label>
                    <input  type="checkbox" name="permission[appsSetting][sms]" @isset(json_decode($role->permission, true)['appsSetting']['sms']) checked @endisset > SMS Setting</label>
             </td>
             <td>
                 <label>
                    <input type="checkbox" name="permission[appsSetting][social]" @isset(json_decode($role->permission, true)['appsSetting']['social']) checked @endisset > Social Setting</label>
             </td>
         </tr>


     </tbody>
 </table>