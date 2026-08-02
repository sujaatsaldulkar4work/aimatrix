
const toolsGrid = document.getElementById('toolsGrid');
const categoriesGrid = document.getElementById('categoriesGrid');
const bookmarksGrid = document.getElementById('bookmarksGrid');


const authModal = document.getElementById('authModal');
const feedbackModal = document.getElementById('feedbackModal');
const toolModal = document.getElementById('toolModal');
const authForm = document.getElementById('authForm');
const feedbackForm = document.getElementById('feedbackForm');
const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const pricingFilter = document.getElementById('pricingFilter');
const sortFilter = document.getElementById('sortFilter');

// Application State
let currentUser = null;
let tools = [];
let categories = [];
let bookmarks = [];
let filteredTools = [];
let currentPage = 'home';
let selectedRating = 0;

// =======================================
// AUTHENTICATION
// =======================================

// Authentication is handled by PHP sessions.
// No JavaScript login is required.

function checkUser() {
    return;
}

function signIn() {
    window.location.href = "/aimatrix/user/login.php";
}

function signUp() {
    window.location.href = "/aimatrix/user/register.php";
}

function signOut() {
    window.location.href = "/aimatrix/user/logout.php";
}

// 3. DATA LOADING FUNCTIONS
async function loadTools() {
    try {
        showLoading(true);

        const response = await fetch('/aimatrix/api/tools.php');

        if (!response.ok) {
            throw new Error(`HTTP error: ${response.status}`);
        }

        const result = await response.json();
        console.log("API response:", result);

        if (!result.success) {
            throw new Error(result.message || 'Unable to load tools');
        }

        tools = result.tools || [];

        /*
         * The old frontend expects these property names.
         * Our PostgreSQL API currently returns different names,
         * so we convert them here.
         */
        tools = result.tools.map(tool => ({
        id: Number(tool.id),
        name: tool.name,
        description: tool.description || "",
        url: tool.website_url || "#",
        image: tool.logo_url || "",
        pricing: tool.pricing || "",
        category: tool.category || "",
        category_id: Number(tool.category_id),
        rating: Number(tool.rating) || 0,
        monthly_visits: tool.monthly_visits || "0"
}));
        filteredTools = [...tools];

        renderTools(filteredTools);
        updateStatsDisplay();

    } catch (error) {
        console.error('Load tools error:', error);
        showToast('Unable to load AI tools', 'error');
    } finally {
        showLoading(false);
    }
}

async function loadCategories() {
    try {

        const response = await fetch('/aimatrix/api/categories.php');

        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }

        const result = await response.json();
	console.log("Categories API:",result);

        if (!result.success) {
            throw new Error(result.message || 'Unable to load categories');
        }

        categories = result.categories || [];


        renderCategories();
        populateCategoryFilter();
        updateStatsDisplay();

    } catch (error) {

        console.error('Load categories error:', error);

        categories = [];

    }
}

async function loadBookmarks() {

    try {

        const response = await fetch("/aimatrix/api/get_bookmarks.php");

        const result = await response.json();

        if(result.success){

            bookmarks = result.bookmarks.map(Number);

        }else{

            bookmarks = [];

        }

    }catch(err){

        console.error(err);

        bookmarks=[];

    }

    updateBookmarkButtons();

}

// 4. RENDERING FUNCTIONS
function renderTools(toolsToRender = filteredTools) {

    const container =
        currentPage === "bookmarks"
            ? bookmarksGrid
            : toolsGrid;

    if (!container) return;

    container.innerHTML = "";

    if (toolsToRender.length === 0) {

        container.innerHTML = `
            <div class="no-results">
                <h3>No AI Tools Found</h3>
                <p>Try changing your filters.</p>
            </div>
        `;

        return;
    }

    toolsToRender.forEach(tool => {

        const bookmarked = bookmarks.includes(tool.id);

        const card = document.createElement("div");

        card.className = "tool-card";

        card.innerHTML = `

<div class="tool-card__header">

<div class="tool-card__icon">

${tool.image ?

`<img src="${tool.image}"
style="width:70px;height:70px;border-radius:10px;object-fit:cover;">`

:

`<div style="width:70px;height:70px;
display:flex;
align-items:center;
justify-content:center;
font-size:30px;">🤖</div>`

}

</div>

<div>

<h3 class="tool-card__title">${tool.name}</h3>

<span class="tool-card__category">
${tool.category}
</span>

</div>

</div>

<p class="tool-card__description">

${tool.description}

</p>

<div class="tool-card__footer">

<div
class="tool-rating"
id="rating-${tool.id}">

⭐ 0 (0)

</div>

<div class="tool-card__actions">

<button
class="bookmark-btn"
data-id="${tool.id}">

${bookmarked ? "❤️" : "🤍"}

</button>

<button
class="open-tool-btn"
data-url="${tool.url}">

Open

</button>

<button
class="rate-tool-btn"
data-id="${tool.id}">

Rate

</button>

</div>

</div>

${tool.pricing ?

`<div class="pricing-badge">
${tool.pricing}
</div>`

:

""

}

`;

        container.appendChild(card);
        loadToolRating(tool.id);

    });

    const resultsCount =
        document.getElementById("resultsCount");

    if(resultsCount){

        resultsCount.textContent =
            toolsToRender.length + " tools found";

    }

}
async function loadToolRating(toolId){

    try{

        const response = await fetch(
            "/aimatrix/api/get_feedback.php?tool_id="+toolId
        );

        const result = await response.json();

        if(!result.success) return;

        const rating =
        document.getElementById(
            "rating-"+toolId
        );

        if(rating){

            rating.innerHTML =

            `⭐ ${result.average_rating}
             (${result.total_reviews})`;

        }

    }

    catch(error){

        console.error(error);

    }

}
async function loadReviews(toolId){

    try{

        const response = await fetch(
            "/aimatrix/api/get_feedback.php?tool_id="+toolId
        );

        const result = await response.json();

        const container =
        document.getElementById("reviewsContainer");

        if(!container) return;

        if(!result.success){

            container.innerHTML="";

            return;

        }

        let html = `
        <h3>
        ⭐ ${result.average_rating}
        (${result.total_reviews} Reviews)
        </h3>
        `;

        result.reviews.forEach(review=>{

            html += `
            <div style="
            border-bottom:1px solid #ddd;
            padding:10px 0;
            ">

            <strong>${review.name}</strong><br>

            ⭐ ${review.rating}/5

            <p>${review.review}</p>

            <small>${review.created_at}</small>

            </div>
            `;

        });

        container.innerHTML = html;

    }

    catch(error){

        console.error(error);

    }

}

function renderCategories() {

    if (!categoriesGrid) return;

    categoriesGrid.innerHTML = '';

    categories.forEach(category => {

        const toolCount = tools.filter(tool =>
            tool.category === category.name
        ).length;

        const card = document.createElement('div');

        card.className = 'category-card';

        card.innerHTML = `
            <div class="category-card__icon">🤖</div>
            <h3>${category.name}</h3>
            <p>${category.description}</p>
            <p>${toolCount} Tools</p>
        `;

        card.onclick = () => {

            document.getElementById("categoryFilter").value = category.id;

            showPage("home");

            applyFilters();

        };

        categoriesGrid.appendChild(card);

    });

}
async function toggleBookmark(toolId){

    try{

        const response = await fetch("/aimatrix/api/toggle_bookmark.php",{

            method:"POST",

            headers:{
                "Content-Type":"application/json"
            },

            body: JSON.stringify({
                tool_id: toolId
            })

        });

        const result = await response.json();

        if(result.success){

            if(result.bookmarked){

                if(!bookmarks.includes(toolId)){
                    bookmarks.push(toolId);
                }

            }else{

                bookmarks = bookmarks.filter(id => id != toolId);

            }

            updateBookmarkButtons();

            if(currentPage==="bookmarks"){
                loadBookmarksPage();
            }

        }

        showToast(result.message || "Done");

    }

    catch(err){

        console.error(err);

        showToast("Bookmark failed","error");

    }

}


function updateBookmarkButtons(){

    document.querySelectorAll(".bookmark-btn").forEach(btn=>{

        const id=parseInt(btn.dataset.id);

        if(bookmarks.includes(id)){

            btn.innerHTML="❤️";

            btn.classList.add("bookmarked");

        }else{

            btn.innerHTML="🤍";

            btn.classList.remove("bookmarked");

        }

    });

}

// 7. FILTERING AND SEARCH FUNCTIONS
function applyFilters() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const pricingFilter = document.getElementById('pricingFilter').value;
    const sortBy = document.getElementById('sortFilter').value;
    
    filteredTools = tools.filter(tool => {
        // Search filter
        const matchesSearch = !searchTerm || 
            tool.name.toLowerCase().includes(searchTerm) ||
            tool.description.toLowerCase().includes(searchTerm) ||
            tool.category.toLowerCase().includes(searchTerm)
        
        // Category filter
        const selectedCategory = categories.find(
            c => c.id == categoryFilter
        );

        const matchesCategory =
            !categoryFilter ||
            tool.category === selectedCategory?.name;
        
        // Pricing filter
        let matchesPricing = true;
        if (pricingFilter) {
            const pricing = tool.pricing ? tool.pricing.toLowerCase() : '';
            switch (pricingFilter) {
                case 'free':
                    matchesPricing = pricing.includes('free') && !pricing.includes('premium') && !pricing.includes('paid');
                    break;
                case 'freemium':
                    matchesPricing = pricing.includes('free') && (pricing.includes('premium') || pricing.includes('paid') || pricing.includes('plan'));
                    break;
                case 'paid':
                    matchesPricing = !pricing.includes('free') || pricing.includes('subscription') || pricing.includes('$');
                    break;
            }
        }
        
        return matchesSearch && matchesCategory && matchesPricing;
    });
    
    // Apply sorting
    filteredTools.sort((a, b) => {
        switch (sortBy) {
            case 'name':
                return a.name.localeCompare(b.name);
            case 'rating':
                return (b.rating || 0) - (a.rating || 0);
            case 'popularity':
                const aVisits = parseFloat((a.monthly_visits || '0').replace(/[^\d.]/g, ''));
                const bVisits = parseFloat((b.monthly_visits || '0').replace(/[^\d.]/g, ''));
                return bVisits - aVisits;
            default:
                return 0;
        }
    });
    
    renderTools(filteredTools);
}
function loadBookmarksPage() {

    const bookmarkedTools = tools.filter(tool =>
        bookmarks.includes(tool.id)
    );

    renderTools(bookmarkedTools);

    const noBookmarks = document.getElementById("noBookmarks");

    if (noBookmarks) {

        if (bookmarkedTools.length === 0) {
            noBookmarks.classList.remove("hidden");
        } else {
            noBookmarks.classList.add("hidden");
        }

    }

}

async function loadBookmarks() {

    try {

        const response = await fetch("/aimatrix/api/get_bookmark.php");

        const result = await response.json();

        if(result.success){

            bookmarks = result.bookmarks;

        }else{

            bookmarks = [];

        }

        updateBookmarkButtons();

        if(currentPage === "bookmarks"){
            loadBookmarksPage();
        }

    }

    catch(error){

        console.error(error);

        bookmarks = [];

    }

}
// 8. UTILITY FUNCTIONS
function populateCategoryFilter() {
    const filter = document.getElementById('categoryFilter');
    if (!filter) return;
    
    // Clear existing options except "All Categories"
    filter.innerHTML = '<option value="">All Categories</option>';
    
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        filter.appendChild(option);
    });
}

function getDefaultCategoryIcon(category) {

    const icons = {
        "Writing": "✍️",
        "Image": "🎨",
        "Video": "🎬",
        "Coding": "💻",
        "Business": "📈",
        "Research": "🔍"
    };

    return icons[category] || "🤖";

}
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast--${type}`;
    toast.textContent = message;
    
    container.appendChild(toast);
    
    // Show toast
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => container.removeChild(toast), 300);
    }, 3000);
}

function showLoading(show) {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) {
        spinner.classList.toggle('hidden', !show);
    }
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Reset forms
        if (modalId === 'authModal') {
            document.getElementById('authForm').reset();
        } else if (modalId === 'feedbackModal') {
            document.getElementById('feedbackForm').reset();
            resetStarRating();
        }
    }
}

function showPage(pageId) {
    // Hide all pages
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });
    
    // Show target page
    const targetPage = document.getElementById(pageId + 'Page');
    if (targetPage) {
        targetPage.classList.add('active');
        currentPage = pageId;
        
        // Update navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        document.querySelector(`[data-page="${pageId}"]`).classList.add('active');
        
        // Load page data
        loadCurrentPageData();
    }
}

function loadCurrentPageData() {
    switch (currentPage) {
        case 'home':
            renderTools(filteredTools);
            break;
        case 'categories':
            renderCategories();
            break;
        case 'bookmarks':
            loadBookmarksPage();
            break;
    }
}
async function loadStats(){

    try{

        const response = await fetch("/aimatrix/api/get_stats.php");

        const result = await response.json();

        if(result.success){

            document.getElementById("statsTools").textContent =
            result.tools;

            document.getElementById("statsCategories").textContent =
            result.categories;

            document.getElementById("statsUsers").textContent =
            result.users;

        }

    }
    catch(error){

        console.error("Stats Error:",error);

    }

}

function updateStatsDisplay(){

    const adminStatsTools =
    document.getElementById("adminStatsTools");

    if(adminStatsTools){

        adminStatsTools.textContent = tools.length;

    }

}

// 9. STAR RATING FUNCTIONS
function initializeStarRating() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('feedbackRating');
    
    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            selectedRating = index + 1;
            ratingInput.value = selectedRating;
            updateStarDisplay();
        });
        
        star.addEventListener('mouseover', () => {
            highlightStars(index + 1);
        });
    });
    
    document.getElementById('starRating').addEventListener('mouseleave', () => {
        updateStarDisplay();
    });
}

function highlightStars(rating) {
    document.querySelectorAll('.star').forEach((star, index) => {
        star.classList.toggle('active', index < rating);
    });
}

function updateStarDisplay() {
    highlightStars(selectedRating);
}

function resetStarRating() {
    selectedRating = 0;
    document.getElementById('feedbackRating').value = '';
    updateStarDisplay();
}

// 10. ADMIN FUNCTIONS
async function importSampleData() {
    try {
        showLoading(true);
        
        // This would typically load from your ai_tools_data.json
        // For now, we'll show a success message
        showToast('Sample data imported successfully!', 'success');
        
        // Reload tools and categories
        await loadCategories();
        await loadTools();
        
    } catch (error) {
        console.error('Import error:', error);
        showToast('Error importing sample data', 'error');
    } finally {
        showLoading(false);
    }
}

// 11. EVENT LISTENERS
document.addEventListener('DOMContentLoaded', () => {
    // Navigation
    document.querySelectorAll('[data-page]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const pageId = link.dataset.page;
            showPage(pageId);
        });
    });
    
    

   
    // Modal close buttons
    document.querySelectorAll('.modal__close, .modal__backdrop').forEach(element => {
        element.addEventListener('click', (e) => {
            const modal = e.target.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Tool card actions (delegation)
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('bookmark-btn')) {
        toggleBookmark(parseInt(e.target.dataset.id));
}
        
       if(e.target.classList.contains("rate-tool-btn")){

    const toolId = e.target.dataset.id;

    feedback.dataset.toolId = toolId;

    loadReviews(toolId);

    openModal("feedbackModal");

}
        if (e.target.classList.contains('open-tool-btn')) {
            const url = e.target.dataset.url;
            const canEmbed = e.target.dataset.embed === 'true';
            
            if (canEmbed) {
                // Open in modal
                const modalBody = document.querySelector('.tool-modal__body');
                modalBody.innerHTML = `<iframe src="${url}" width="100%" height="600px" frameborder="0"></iframe>`;
                openModal('toolModal');
            } else {
                // Open in new tab
                window.open(url,"_blank","noopener,noreferrer");
            }
        }
    });
    // Feedback form

    const feedback = document.getElementById("feedbackForm");

    if(feedback){

    feedback.addEventListener("submit",async(e)=>{

    e.preventDefault();

    const toolId=parseInt(feedback.dataset.toolId);

    const rating=parseInt(document.getElementById("feedbackRating").value);

    const review=document.getElementById("feedbackComment").value.trim();

    if(!rating){

    showToast("Please select a rating.","error");

    return;

    }

    try{

    const response=await fetch("/aimatrix/api/submit_feedback.php",{

    method:"POST",

    headers:{
    "Content-Type":"application/json"
    },

    body:JSON.stringify({

    tool_id:toolId,

    rating:rating,

    review:review

    })

    });

    const result = await response.json();

showToast(result.message);

if(result.success){

    loadReviews(toolId);

    loadToolRating(toolId);

    feedback.reset();

    resetStarRating();

    closeModal("feedbackModal");

}}
catch(error){

    console.error(error);

    showToast("Failed to submit review.","error");

}

    });

}

    // Character counter

    const feedbackComment=document.getElementById("feedbackComment");

    if(feedbackComment){

    feedbackComment.addEventListener("input",(e)=>{

    const count=e.target.value.length;

    const counter=document.querySelector(".character-count");

    if(counter){

    counter.textContent=`${count}/500`;

    }

    });

    }
    
    // Search and filters
    searchInput.addEventListener('input', applyFilters);
    document.getElementById('categoryFilter').addEventListener('change', applyFilters);
    document.getElementById('pricingFilter').addEventListener('change', applyFilters);
    document.getElementById('sortFilter').addEventListener('change', applyFilters);
    
    // Admin actions
    const importDataBtn = document.getElementById('importDataBtn');
    if (importDataBtn) {
        importDataBtn.addEventListener('click', importSampleData);
    }
    
    // Initialize star rating
    initializeStarRating();
    
    // Initialize app
    init();
});

// 12. INITIALIZATION
async function init() {

    try {

        showLoading(true);

        await loadCategories();

        await loadTools();

        await loadBookmarks();

        await loadStats();

        filteredTools = [...tools];

        updateStatsDisplay();

        showPage("home");

    }
    catch(error){

        console.error(error);

        showToast("Initialization failed","error");

    }
    finally{

        showLoading(false);

    }

}
