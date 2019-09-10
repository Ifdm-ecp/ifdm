<style> 
   .required:after{ 
   content:'*'; 
   color:red; 
   }
   #sticky {
   padding: 0.5ex;
   background-color: #333;
   color: #fff;
   width: 100%;
   font-size: 1.5em;
   border-radius: 0.5ex;
   }
   #sticky.stick {
   position: fixed;
   top: 0;
   width: 62.7%;
   z-index: 10000;
   border-radius: 0 0 0.5em 0.5em;
   }
   #container.handsontable table{
   width:100%;
   }
   #loading{
   width: 50px;
   height: 50px;
   border: 5px solid #ccc;
   border-top-color: #ff6a00;
   border-radius: 100%;
   position: fixed;
   left: 0;
   right: 0;
   top: 0;
   bottom: 0;
   margin: auto;
   animation: round 2s linear infinite;
   z-index: 9999;
   }
   @keyframes round{
   from{transform: rotate(0deg)}
   to{transform: rotate(360deg)}
   }
</style>