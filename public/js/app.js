document.querySelector('.menu-toggle')?.addEventListener('click',function(){const nav=document.getElementById('navigation');const open=nav.classList.toggle('open');this.setAttribute('aria-expanded',String(open));this.setAttribute('aria-label',open?'Close navigation':'Open navigation');});
document.addEventListener('click',event=>{document.querySelectorAll('.nav-dropdown[open]').forEach(el=>{if(!el.contains(event.target)){el.open=false;}});});
document.addEventListener('keydown',event=>{if(event.key==='Escape'){document.querySelectorAll('.nav-dropdown[open]').forEach(el=>el.open=false);const menu=document.querySelector('.menu-toggle');if(document.getElementById('navigation')?.classList.contains('open')){menu.click();menu.focus();}}});
document.querySelectorAll('[data-billing]').forEach(button=>button.addEventListener('click',()=>{const period=button.dataset.billing;document.querySelectorAll('[data-billing]').forEach(b=>b.classList.toggle('selected',b===button));document.querySelectorAll('[data-monthly]').forEach(el=>{const price=Number(el.dataset[period]);el.textContent='$'+price.toLocaleString('en-AU',{maximumFractionDigits:2});if(Number(el.dataset.monthly)>0){el.nextElementSibling.textContent=period==='yearly'?'/ year':'/ month';}});document.querySelectorAll('.billing-input').forEach(el=>el.value=period);}));
document.querySelectorAll('[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{if(!window.confirm(form.dataset.confirm)){event.preventDefault();}}));

const dashboardThemeToggle=document.querySelector('[data-dashboard-theme-toggle]');
const applyDashboardTheme=theme=>{document.documentElement.dataset.theme=theme;const label=theme==='dark'?'Switch to light mode':'Switch to dark mode';dashboardThemeToggle?.setAttribute('aria-label',label);dashboardThemeToggle?.setAttribute('title',label);};
if(dashboardThemeToggle){applyDashboardTheme(document.documentElement.dataset.theme||'light');dashboardThemeToggle.addEventListener('click',()=>{const theme=document.documentElement.dataset.theme==='dark'?'light':'dark';applyDashboardTheme(theme);try{localStorage.setItem('admin-theme',theme);}catch(error){}});}

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

const formFieldPlaceholders={name:'e.g. Priya Sharma',email:'name@example.com',phone:'e.g. +61 400 000 000',password:'Enter your password',password_confirmation:'Re-enter your password',quantity:'e.g. 100',buy_price:'e.g. 24.50',alert_price:'e.g. 30.00',duration_seconds:'e.g. 120',company:'e.g. Acme Investments',details:'Add relevant details or notes…',message:'Tell us how we can help…',alt_text:'Describe what the image shows…'};
document.querySelectorAll('form input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="submit"]),form textarea').forEach(field=>{
    if(field.hasAttribute('placeholder'))return;
    const label=field.id?document.querySelector(`label[for="${field.id}"]`):field.closest('label');
    const labelText=label?.textContent.replace(/\*/g,'').replace(/\s+/g,' ').trim();
    field.placeholder=formFieldPlaceholders[field.name]||formFieldPlaceholders[field.type]||(labelText?`${labelText}…`:'Enter details…');
});
const retirementForm=document.getElementById('retirement-form');
if(retirementForm){const calculate=()=>{const current=Number(document.getElementById('current-age').value);const retire=Number(document.getElementById('retirement-age').value);const ageInput=document.getElementById('retirement-age');ageInput.setCustomValidity(retire<=current?'Retirement age must be greater than current age.':'');if(!retirementForm.reportValidity()){return;}const initial=Number(document.getElementById('savings').value);const monthly=Number(document.getElementById('contribution').value);const rate=Math.pow(1+Number(document.getElementById('return-rate').value)/100,1/12)-1;const months=(retire-current)*12;const factor=Math.pow(1+rate,months);const total=initial*factor+(Math.abs(rate)<1e-10?monthly*months:monthly*(factor-1)/rate);const paid=initial+monthly*months;document.getElementById('projection-value').textContent=new Intl.NumberFormat('en-AU',{style:'currency',currency:'AUD',maximumFractionDigits:0}).format(total);document.getElementById('projection-summary').textContent='Illustrative savings at age '+retire+' after '+(retire-current)+' years. Total starting savings and contributions: '+new Intl.NumberFormat('en-AU',{style:'currency',currency:'AUD',maximumFractionDigits:0}).format(paid)+'.';document.getElementById('contribution-bar').style.width=(total>0?Math.min(100,paid/total*100):0)+'%';};retirementForm.addEventListener('submit',event=>{event.preventDefault();calculate();});retirementForm.addEventListener('input',()=>document.getElementById('retirement-age').setCustomValidity(''));calculate();}

const categoryCarousel=document.querySelector('[data-category-carousel]');
if(categoryCarousel){
    const reducedMotionQuery=window.matchMedia('(prefers-reduced-motion: reduce)');
    const originalCategoryCards=Array.from(categoryCarousel.children);
    let carouselClones=[];
    let carouselTimer;
    let carouselResetTimer;
    let carouselResumeTimer;
    let carouselScrollTimer;
    let carouselResizeTimer;
    let carouselIndex=0;

    const categoryCardStep=()=>{
        const firstCard=categoryCarousel.firstElementChild;
        const styles=window.getComputedStyle(categoryCarousel);

        return firstCard?firstCard.getBoundingClientRect().width+parseFloat(styles.columnGap||styles.gap||0):0;
    };
    const scrollToCategoryCard=(index,smooth=true)=>{
        carouselIndex=index;
        categoryCarousel.style.scrollBehavior=smooth?'smooth':'auto';
        categoryCarousel.scrollLeft=carouselIndex*categoryCardStep();
        if(!smooth){requestAnimationFrame(()=>categoryCarousel.style.scrollBehavior='smooth');}
    };
    const stopCategoryCarousel=()=>window.clearInterval(carouselTimer);
    const resetCategoryCarousel=()=>{
        window.clearTimeout(carouselResetTimer);
        scrollToCategoryCard(0,false);
    };
    const advanceCategoryCarousel=()=>{
        const step=categoryCardStep();

        if(!step){return;}

        scrollToCategoryCard(carouselIndex+1,!reducedMotionQuery.matches);
        if(carouselIndex===originalCategoryCards.length){
            carouselResetTimer=window.setTimeout(resetCategoryCarousel,650);
        }
    };
    const startCategoryCarousel=()=>{
        stopCategoryCarousel();
        if(document.hidden||originalCategoryCards.length<2){return;}
        carouselTimer=window.setInterval(advanceCategoryCarousel,3000);
    };
    const setupCategoryCarousel=()=>{
        if(originalCategoryCards.length<2){
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
                categoryCarousel.appendChild(clone);

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
            carouselIndex=step?Math.round(categoryCarousel.scrollLeft/step)%originalCategoryCards.length:0;
            startCategoryCarousel();
        },2500);
    };

    categoryCarousel.addEventListener('pointerdown',pauseForInteraction);
    categoryCarousel.addEventListener('pointerup',resumeAfterInteraction);
    categoryCarousel.addEventListener('pointercancel',resumeAfterInteraction);
    categoryCarousel.addEventListener('focusin',pauseForInteraction);
    categoryCarousel.addEventListener('focusout',resumeAfterInteraction);
    document.querySelector('[data-category-prev]')?.addEventListener('click',()=>{
        pauseForInteraction();
        const currentIndex=Math.round(categoryCarousel.scrollLeft/categoryCardStep())%originalCategoryCards.length;
        if(currentIndex===0){
            scrollToCategoryCard(originalCategoryCards.length,false);
            requestAnimationFrame(()=>scrollToCategoryCard(originalCategoryCards.length-1,true));
        }else{
            scrollToCategoryCard(currentIndex-1,true);
        }
        resumeAfterInteraction();
    });
    document.querySelector('[data-category-next]')?.addEventListener('click',()=>{
        pauseForInteraction();
        advanceCategoryCarousel();
        resumeAfterInteraction();
    });
    categoryCarousel.addEventListener('scroll',()=>{
        window.clearTimeout(carouselScrollTimer);
        carouselScrollTimer=window.setTimeout(()=>{
            const step=categoryCardStep();
            if(step&&categoryCarousel.scrollLeft>=step*originalCategoryCards.length-2){resetCategoryCarousel();}
        },120);
    });
    document.addEventListener('visibilitychange',startCategoryCarousel);
    window.addEventListener('resize',()=>{
        window.clearTimeout(carouselResizeTimer);
        carouselResizeTimer=window.setTimeout(setupCategoryCarousel,150);
    });
    setupCategoryCarousel();
}

document.querySelectorAll('[data-testimonials-carousel]').forEach(carousel=>{
    const originalTestimonialCards=Array.from(carousel.children);
    const reducedMotionQuery=window.matchMedia('(prefers-reduced-motion: reduce)');
    let carouselClones=[];
    let carouselTimer;
    let carouselResetTimer;
    let resumeTimer;
    let carouselScrollTimer;
    let carouselResizeTimer;
    let carouselIndex=0;

    const testimonialCardStep=()=>{
        const firstCard=carousel.firstElementChild;
        const styles=window.getComputedStyle(carousel);

        return firstCard?firstCard.getBoundingClientRect().width+parseFloat(styles.columnGap||styles.gap||0):0;
    };
    const scrollToTestimonial=(index,smooth=true)=>{
        carouselIndex=index;
        carousel.style.scrollBehavior=smooth?'smooth':'auto';
        carousel.scrollLeft=carouselIndex*testimonialCardStep();
        if(!smooth){requestAnimationFrame(()=>carousel.style.scrollBehavior='smooth');}
    };
    const stopCarousel=()=>window.clearInterval(carouselTimer);
    const resetCarousel=()=>{
        window.clearTimeout(carouselResetTimer);
        scrollToTestimonial(0,false);
    };
    const advanceCarousel=()=>{
        const step=testimonialCardStep();

        if(!step){return;}

        scrollToTestimonial(carouselIndex+1,!reducedMotionQuery.matches);
        if(carouselIndex===originalTestimonialCards.length){
            carouselResetTimer=window.setTimeout(resetCarousel,650);
        }
    };
    const startCarousel=()=>{
        stopCarousel();
        if(document.hidden||originalTestimonialCards.length<2){return;}
        carouselTimer=window.setInterval(advanceCarousel,3000);
    };
    const setupCarousel=()=>{
        if(originalTestimonialCards.length<2){return;}
        if(carouselClones.length===0){
            carouselClones=originalTestimonialCards.map(card=>{
                const clone=card.cloneNode(true);
                clone.setAttribute('aria-hidden','true');
                carousel.appendChild(clone);

                return clone;
            });
        }
        resetCarousel();
        startCarousel();
    };
    const pauseCarousel=()=>{
        stopCarousel();
        window.clearTimeout(carouselResetTimer);
        window.clearTimeout(resumeTimer);
    };
    const resumeCarousel=()=>{
        window.clearTimeout(resumeTimer);
        resumeTimer=window.setTimeout(()=>{
            const step=testimonialCardStep();
            carouselIndex=step?Math.round(carousel.scrollLeft/step)%originalTestimonialCards.length:0;
            startCarousel();
        },2500);
    };

    carousel.addEventListener('pointerdown',pauseCarousel);
    carousel.addEventListener('pointerup',resumeCarousel);
    carousel.addEventListener('pointercancel',resumeCarousel);
    carousel.addEventListener('focusin',pauseCarousel);
    carousel.addEventListener('focusout',resumeCarousel);
    document.querySelector('[data-testimonial-prev]')?.addEventListener('click',()=>{
        pauseCarousel();
        const currentIndex=Math.round(carousel.scrollLeft/testimonialCardStep())%originalTestimonialCards.length;
        if(currentIndex===0){
            scrollToTestimonial(originalTestimonialCards.length,false);
            requestAnimationFrame(()=>scrollToTestimonial(originalTestimonialCards.length-1,true));
        }else{
            scrollToTestimonial(currentIndex-1,true);
        }
        resumeCarousel();
    });
    document.querySelector('[data-testimonial-next]')?.addEventListener('click',()=>{
        pauseCarousel();
        advanceCarousel();
        resumeCarousel();
    });
    carousel.addEventListener('scroll',()=>{
        window.clearTimeout(carouselScrollTimer);
        carouselScrollTimer=window.setTimeout(()=>{
            const step=testimonialCardStep();
            if(step&&carousel.scrollLeft>=step*originalTestimonialCards.length-2){resetCarousel();}
        },120);
    });
    window.addEventListener('resize',()=>{
        window.clearTimeout(carouselResizeTimer);
        carouselResizeTimer=window.setTimeout(setupCarousel,150);
    });
    document.addEventListener('visibilitychange',startCarousel);
    setupCarousel();
});

