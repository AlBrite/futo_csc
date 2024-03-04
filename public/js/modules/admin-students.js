

// <script>
//       function init() {
       
//         if (this.student_id) {

//           api('/student', {student_id:this.student_id})
//           .then(response => {
  
//             this.student = response;
            
//             const nameParts = response.user.name.split(" ");
//             this.firstname = nameParts[0];
//             this.lastname = nameParts.length > 1 ? nameParts[1] : '';
//             this.middlename = nameParts.length > 2 ? nameParts[2] : '';
//           })
//           .catch(error => console.log(error));

//         }

        
//       }
//   </script>