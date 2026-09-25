document.addEventListener("DOMContentLoaded",()=>{
 const modal=document.querySelector("#quote-modal");
 document.querySelectorAll("[data-open-quote]").forEach(b=>b.addEventListener("click",()=>{if(modal)modal.hidden=false}));
 document.querySelectorAll("[data-close-quote]").forEach(b=>b.addEventListener("click",()=>{if(modal)modal.hidden=true}));
 if(modal)modal.addEventListener("click",e=>{if(e.target===modal)modal.hidden=true});
 const area=document.querySelector("#project-area"),out=document.querySelector("#estimate-value");
 let factor=15400000;
 document.querySelectorAll(".calc-tabs button").forEach(b=>b.addEventListener("click",()=>{document.querySelectorAll(".calc-tabs button").forEach(x=>x.classList.remove("active"));b.classList.add("active");factor=Number(b.dataset.factor||factor)}));
 document.querySelector("#calculate-project")?.addEventListener("click",()=>{const v=Math.max(1,Number(area?.value||1))*factor;if(out)out.textContent=new Intl.NumberFormat("fa-IR").format(v)+" ریال"});
 document.querySelectorAll("[data-fill-address]").forEach(b=>b.addEventListener("click",()=>{try{const a=JSON.parse(b.dataset.fillAddress);for(const [key,val] of Object.entries({recipient:a.recipient,phone:a.phone,province:a.province,city:a.city,shipping_address:a.address,postal_code:a.postal_code||""})){const el=document.querySelector(`[name="${key}"]`);if(el)el.value=val}}catch(e){}}));
 setTimeout(()=>document.querySelectorAll(".flash").forEach(x=>x.remove()),4500);
});
