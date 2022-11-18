import axios from "axios"

function request(){
    return {
  ///////////////////////////////////////////////////////////////
  /////////////////////gertDAta////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////
  
      getData: async function(data){
           let  form = data.form !== null ? new FormData(data.form) :new FormData()
                  
                  //console.log(data.appends)    
               if (typeof data.appends !== 'undefined') {     
                data.appends.forEach( (a,i)=>{
                     if(typeof data.keys !== 'undefined'){
                        form.append(data.keys[i],a) 
                     }else{
                        form.append('post'+i,a) 
                     }
                    
                } )
              }
              form.forEach(r=>{
               //  console.log(r)
              })
           
              let options  = { 
                 method:data.method?data.method: 'POST', 
               // mode: 'cors',
                // 'Content-Type': 'application/json',this will not allow json to be pass
                headers:  data.header || 
                                       {
                                        "Accept":"application/json",
                                        "X-CSRF-TOKEN":data.token
                                    },
               // body: form
            }
             
              
             let post_data  = {}
             for (let i = 0; i < data.keys.length; i++) {
                post_data[data.keys[i]]  = data.appends[i];
             }

             if(!data.body){
                options['body'] = JSON.stringify(post_data)
             }else{
                options['body'] = form
             }
          
                var request = new Request(data.url, options);                   
              try{
                const fetchResult = fetch(request)
                const response = await fetchResult;
                const jsonData = await response.json();
            return  {bol:true, data: jsonData  }
            
            }catch(e){
             
  
              return {bol:false, data:{err:"Network refuse connection",e:e } } ;
  
  
            }
  
  
  
  },///////////////////////////////////////////////////////////////
  /////////////////////gertDAta////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////
  
  
  
  
    }////////////////End of object return///////////////
    //////////////////////////////
  
  
  
  
  }
  

  export const axios_request  = async (data)=>{
    let  form = data.form !== null ? new FormData(data.form) :new FormData()
                  
    //console.log(data.appends)    
 if (typeof data.appends !== 'undefined') {     
  data.appends.forEach( (a,i)=>{
       if(typeof data.keys !== 'undefined'){
          form.append(data.keys[i],a) 
       }else{
          form.append('post'+i,a) 
       }
      
  } )
}
 
// const agent = new https.Agent({  
//     rejectUnauthorized: false
//   });
    const options = {
        method: 'POST',
        headers:  data.header|| { 'Accept': 'application/json'},
        data: form,
        url: data.url,
       // httpsAgent: agent
      };
    
    // console.log(options)
     
         try {
          let d  = await axios(options)
        
    
           return d 

         } catch (error) {
            console.log(error)
         }

         
  }

  
  export default request
  
  
  
  
  
  
  
  
  
  