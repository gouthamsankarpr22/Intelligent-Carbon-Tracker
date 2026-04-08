<style>

body{
opacity:0;
transform:translateY(15px);
transition:opacity 0.4s ease, transform 0.4s ease;
}

body.loaded{
opacity:1;
transform:translateY(0);
}

</style>
<script>

window.addEventListener("load", function(){
document.body.classList.add("loaded");
});

document.querySelectorAll("a").forEach(link=>{
link.addEventListener("click",function(e){

if(this.href && this.target!="_blank"){

e.preventDefault();

document.body.style.opacity="0";
document.body.style.transform="translateY(10px)";

setTimeout(()=>{
window.location=this.href;
},300);

}

});
});

</script>