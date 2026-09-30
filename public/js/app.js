document.querySelector('.menu-toggle')?.addEventListener('click',function(){const nav=document.getElementById('navigation');const open=nav.classList.toggle('open');this.setAttribute('aria-expanded',String(open));this.setAttribute('aria-label',open?'Close navigation':'Open navigation');});
document.addEventListener('click',event=>{document.querySelectorAll('.nav-dropdown[open]').forEach(el=>{if(!el.contains(event.target)){el.open=false;}});});
document.addEventListener('keydown',event=>{if(event.key==='Escape'){document.querySelectorAll('.nav-dropdown[open]').forEach(el=>el.open=false);const menu=document.querySelector('.menu-toggle');if(document.getElementById('navigation')?.classList.contains('open')){menu.click();menu.focus();}}});
document.querySelectorAll('[data-billing]').forEach(button=>button.addEventListener('click',()=>{const period=button.dataset.billing;document.querySelectorAll('[data-billing]').forEach(b=>b.classList.toggle('selected',b===button));document.querySelectorAll('[data-monthly]').forEach(el=>{const price=Number(el.dataset[period]);el.textContent='$'+price.toLocaleString('en-AU',{maximumFractionDigits:2});if(Number(el.dataset.monthly)>0){el.nextElementSibling.textContent=period==='yearly'?'/ year':'/ month';}});document.querySelectorAll('.billing-input').forEach(el=>el.value=period);}));
document.querySelectorAll('[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{if(!window.confirm(form.dataset.confirm)){event.preventDefault();}}));

const marketMarquee=document.querySelector('.market-marquee');
if(marketMarquee){
    const marketTrack=marketMarquee.querySelector('.market-track');
    const marketSource=marketTrack?.querySelector('.market-group:not([data-market-clone])');
    let marketResizeTimer;

    const buildMarketLoop=()=>{
        if(!marketTrack||!marketSource){return;}
        marketTrack.querySelectorAll('[data-market-clone]').forEach(clone=>clone.remove());
        const groupWidth=marketSource.getBoundingClientRect().width;
        if(!groupWidth){return;}

        const requiredWidth=marketMarquee.clientWidth+groupWidth;
        while(marketTrack.scrollWidth<requiredWidth){
            const clone=marketSource.cloneNode(true);
            clone.setAttribute('data-market-clone','');
            clone.setAttribute('aria-hidden','true');
            marketTrack.appendChild(clone);
        }

        marketTrack.style.setProperty('--market-shift',`-${groupWidth}px`);
    };

    requestAnimationFrame(buildMarketLoop);
    window.addEventListener('resize',()=>{
        window.clearTimeout(marketResizeTimer);
        marketResizeTimer=window.setTimeout(buildMarketLoop,150);
    });
}
const retirementForm=document.getElementById('retirement-form');
if(retirementForm){const calculate=()=>{const current=Number(document.getElementById('current-age').value);const retire=Number(document.getElementById('retirement-age').value);const ageInput=document.getElementById('retirement-age');ageInput.setCustomValidity(retire<=current?'Retirement age must be greater than current age.':'');if(!retirementForm.reportValidity()){return;}const initial=Number(document.getElementById('savings').value);const monthly=Number(document.getElementById('contribution').value);const rate=Math.pow(1+Number(document.getElementById('return-rate').value)/100,1/12)-1;const months=(retire-current)*12;const factor=Math.pow(1+rate,months);const total=initial*factor+(Math.abs(rate)<1e-10?monthly*months:monthly*(factor-1)/rate);const paid=initial+monthly*months;document.getElementById('projection-value').textContent=new Intl.NumberFormat('en-AU',{style:'currency',currency:'AUD',maximumFractionDigits:0}).format(total);document.getElementById('projection-summary').textContent='Illustrative savings at age '+retire+' after '+(retire-current)+' years. Total starting savings and contributions: '+new Intl.NumberFormat('en-AU',{style:'currency',currency:'AUD',maximumFractionDigits:0}).format(paid)+'.';document.getElementById('contribution-bar').style.width=(total>0?Math.min(100,paid/total*100):0)+'%';};retirementForm.addEventListener('submit',event=>{event.preventDefault();calculate();});retirementForm.addEventListener('input',()=>document.getElementById('retirement-age').setCustomValidity(''));calculate();}

const mobileCategoryCarousel=document.querySelector('[data-mobile-carousel]');
if(mobileCategoryCarousel){
    const mobileCarouselQuery=window.matchMedia('(max-width: 640px)');
    const reducedMotionQuery=window.matchMedia('(prefers-reduced-motion: reduce)');
    const originalCategoryCards=Array.from(mobileCategoryCarousel.children);
    let carouselClones=[];
    let carouselTimer;
    let carouselResetTimer;
    let carouselResumeTimer;
    let carouselScrollTimer;
    let carouselIndex=0;

    const categoryCardStep=()=>{
        const firstCard=mobileCategoryCarousel.firstElementChild;
        const styles=window.getComputedStyle(mobileCategoryCarousel);

        return firstCard?firstCard.getBoundingClientRect().width+parseFloat(styles.columnGap||styles.gap||0):0;
    };
    const stopCategoryCarousel=()=>window.clearInterval(carouselTimer);
    const resetCategoryCarousel=()=>{
        window.clearTimeout(carouselResetTimer);
        carouselIndex=0;
        mobileCategoryCarousel.scrollTo({left:0,behavior:'auto'});
    };
    const advanceCategoryCarousel=()=>{
        const step=categoryCardStep();

        if(!step){return;}

        carouselIndex+=1;
        mobileCategoryCarousel.scrollTo({left:carouselIndex*step,behavior:'smooth'});
        if(carouselIndex===originalCategoryCards.length){
            carouselResetTimer=window.setTimeout(resetCategoryCarousel,650);
        }
    };
    const startCategoryCarousel=()=>{
        stopCategoryCarousel();
        if(!mobileCarouselQuery.matches||reducedMotionQuery.matches||document.hidden||originalCategoryCards.length<2){return;}
        carouselTimer=window.setInterval(advanceCategoryCarousel,3500);
    };
    const setupCategoryCarousel=()=>{
        if(!mobileCarouselQuery.matches){
            stopCategoryCarousel();
            carouselClones.forEach(card=>card.remove());
            carouselClones=[];
            resetCategoryCarousel();
            return;
        }

        if(carouselClones.length===0){
            carouselClones=originalCategoryCards.map(card=>{
                const clone=card.cloneNode(true);
                clone.setAttribute('aria-hidden','true');
                clone.setAttribute('tabindex','-1');
                mobileCategoryCarousel.appendChild(clone);

                return clone;
            });
        }
        resetCategoryCarousel();
        startCategoryCarousel();
    };
    const pauseForInteraction=()=>{
        stopCategoryCarousel();
        window.clearTimeout(carouselResetTimer);
        window.clearTimeout(carouselResumeTimer);
    };
    const resumeAfterInteraction=()=>{
        window.clearTimeout(carouselResumeTimer);
        carouselResumeTimer=window.setTimeout(()=>{
            const step=categoryCardStep();
            carouselIndex=step?Math.round(mobileCategoryCarousel.scrollLeft/step)%originalCategoryCards.length:0;
            startCategoryCarousel();
        },2500);
    };

    mobileCategoryCarousel.addEventListener('pointerdown',pauseForInteraction);
    mobileCategoryCarousel.addEventListener('pointerup',resumeAfterInteraction);
    mobileCategoryCarousel.addEventListener('pointercancel',resumeAfterInteraction);
    mobileCategoryCarousel.addEventListener('focusin',pauseForInteraction);
    mobileCategoryCarousel.addEventListener('focusout',resumeAfterInteraction);
    mobileCategoryCarousel.addEventListener('scroll',()=>{
        window.clearTimeout(carouselScrollTimer);
        carouselScrollTimer=window.setTimeout(()=>{
            const step=categoryCardStep();
            if(step&&mobileCategoryCarousel.scrollLeft>=step*originalCategoryCards.length-2){resetCategoryCarousel();}
        },120);
    });
    document.addEventListener('visibilitychange',startCategoryCarousel);
    mobileCarouselQuery.addEventListener('change',setupCategoryCarousel);
    reducedMotionQuery.addEventListener('change',startCategoryCarousel);
    setupCategoryCarousel();
}

