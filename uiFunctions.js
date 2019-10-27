/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function select_style(styleName){
    style = document.getElementById("styleID");
    switch(styleName)
    {
        case "Chocolate":
            style.setAttribute('href',"https://www.w3.org/StyleSheets/Core/Chocolate");
            break;
        case "Midnight":
            style.setAttribute('href',"https://www.w3.org/StyleSheets/Core/Midnight");
            break;
        case "Modernist":
            style.setAttribute('href',"https://www.w3.org/StyleSheets/Core/Modernist");
            break;
        case "Oldstyle":
            style.setAttribute('href',"https://www.w3.org/StyleSheets/Core/Oldstyle");
            break;
        case "Steely":
            style.setAttribute('href',"https://www.w3.org/StyleSheets/Core/Steely");
            break;
        case "Swiss":
            style.setAttribute('href',"https://www.w3.org/StyleSheets/Core/Swiss");
            break;
        case "Traditional":
            style.setAttribute('href',"https://www.w3.org/StyleSheets/Core/Traditional");
            break;
        case "Ultramarine":
            style.setAttribute('href',"https://www.w3.org/StyleSheets/Core/Ultramarine");
            break;
        
    }
    
}
