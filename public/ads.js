let ads = []
const adElement = document.querySelector("#ad")    //HTML elements
const initialHeight = getComputedStyle(adElement).height 
const container = document.querySelector("#ad__container")    //HTML elements
const closeBtn = document.querySelector("#ad__close-btn")    //HTML elements
const timeBar = document.querySelector("#ad__time-bar")    //HTML elements

let canShow = true  //Says wheter the current ad time is over or not
let timer = null
let time = 0
let code
let minimumVisibilityTime_ms
let adsHidden = false

function wait(s) {
    return new Promise(resolve => setTimeout(resolve, s * 1000));
}

async function showAd(ad){
    if(adsHidden) return;
    if(canShow){
        minimumVisibilityTime_ms = ad.minimumVisibilityTime * 1000
        code = '<a href="/ads/'+ad.id+'/open" target="_blank">';
        if(ad.fileData.image) code += '<img src="'+ad.fileData.src+'"/>'
        else code += '<video src="'+ad.fileData.src+'" autoplay loop muted id="ad__video">Your browser does not support the video tag.</video>'
        code += '</a>'
        container.innerHTML = code
        //Adapt frame size to image size
        setTimeout(() => {
            if(ad.fileData.image){
                const imgEl = container.querySelector("img")
                adElement.style.height = ((imgEl.naturalHeight / imgEl.naturalWidth) * imgEl.offsetWidth) + "px"
            }else
                adElement.style.height = initialHeight
        }, 100) //Wait the image to be loaded
        try{
            if(ad.fileData.video) document.getElementById("ad__video").muted = false
        }catch(e){}
        closeBtn.style.display = "none"
        timeBar.style.width = "0"
        canShow = false
        if(timer !== null) clearInterval(timer)
        time = 0
        timer = setInterval(function(){
            time += 10
            timeBar.style.width = ((time / minimumVisibilityTime_ms)*100) + "%"
            if(time >= minimumVisibilityTime_ms){
                closeBtn.style.display = "block" //can close after minimumVisibilityTime
                timeBar.style.width = "100%"
                clearInterval(timer)
            }
        }, 10)
        setTimeout(() => {canShow = true}, minimumVisibilityTime_ms + 5000)    //Show at least during minimumVisibilityTime + 5s
    }else{
        await wait(5)    //Wait 5s
        return showAd(ad)
    }
}

/**
 * Show the advertisements in the page
 */
async function showAds(){
    if(adsHidden) return;
    for(let ad of ads) await showAd(ad)    
    showAds()       //Restart once finished
}

/**
 * Start the advertisements process
 */
async function start(){
    //Load ads
    const response = await fetch("/api/ads", {method: "GET"})
    if(!response.ok) return
    const jsonResponse = (await response.json())
    if(!jsonResponse.status) return 
    ads = jsonResponse.data
    if(ads.length > 0){     //there are ads to show
        setTimeout(async () => {
            const lastAdsSessionDate = localStorage.getItem("last-ads-session-date")
            const minutesSinceTheLastSession = (lastAdsSessionDate == null) ? 5 : Math.floor((new Date() - new Date(lastAdsSessionDate)) / 60000)
            if(minutesSinceTheLastSession > 4){     //New ads session after 4 minutes  
                adElement.style.display = "block"  //The box is hidden at first  
                await showAds()
            }
        }, 5000)   //Start after 5s 
    }
}   

start()  

/* EVENTS LISTENERS */
//Close box
closeBtn.addEventListener("click", () => {
    localStorage.setItem("last-ads-session-date", new Date().toLocaleString())
    adElement.style.display = "none"
    try{
        document.getElementById("ad__video").stop()
        document.getElementById("ad__video").muted = true
    }catch(e){}
    adsHidden = true
})