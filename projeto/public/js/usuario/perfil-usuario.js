document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".card");
  
    cards.forEach(card => {
      const hoverContent = card.querySelector(".hover-content");
  
      
      card.addEventListener("click", (e) => {
        e.stopPropagation(); 
       
        document.querySelectorAll(".card .hover-content.active").forEach(opened => {
          if (opened !== hoverContent) {
            opened.classList.remove("active");
          }
        });
        hoverContent.classList.toggle("active");
      });
    });
  
    
    document.addEventListener("click", () => {
      document.querySelectorAll(".card .hover-content.active").forEach(opened => {
        opened.classList.remove("active");
      });
    });
  });
  