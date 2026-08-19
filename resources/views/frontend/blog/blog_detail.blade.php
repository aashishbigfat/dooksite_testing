@extends('frontend.layouts.master')
@push('title') {{$post_detail['meta_title']}} @endpush
@push('meta_tag')
<meta name="keywords" content="{{$post_detail['meta_keywords']}}">
<meta name="description" content="{{$post_detail['meta_description']}}">@endpush
@push('ogtags')
<meta property="og:title" content="{{$post_detail['title']}}">
<meta property="og:type" content="article"/>
<meta property="og:url" content="{{URL::current()}}/">
<meta property="og:description" content="{{ Str::limit(strip_tags(htmlspecialchars_decode($post_detail['short_description'])), 350, ' ...') }}">
<meta property="og:image" content="{{$post_detail['image']}}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:creator" content="@dooktravels">
<meta name="twitter:url" content="{{URL::current()}}/">
<meta name="twitter:title" content="{{$post_detail['title']}}">
<meta name="twitter:description" content="{{ strip_tags(Str::limit($post_detail['short_description'], 350, ' ...')) }}">
<meta name="twitter:image" content="{{$post_detail['image']}}">
@endpush
@push('head_script')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ URL::current() }}/"
  },
  "headline": "{{ $post_detail['title'] }}",
  "description": "{{ Str::limit(strip_tags(htmlspecialchars_decode($post_detail['short_description'])), 350, ' ...') }}",
  "image": "{{ $post_detail['image'] }}",
  "author": {
    "@type": "Person",
    "name": "Dook International",
    "url": "https://www.dookinternational.com"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Dook International Blog",
    "logo": {
      "@type": "ImageObject",
      "url": "https://www.dookinternational.com/assets/images/logo.png"
    }
  },
"datePublished": "{{ \Carbon\Carbon::parse($post_detail['published_date'])->setTimezone('Asia/Kolkata')->format('Y-m-d\\TH:i:sP') }}",
"dateModified": "{{ \Carbon\Carbon::parse($post_detail['modified_date'])->setTimezone('Asia/Kolkata')->format('Y-m-d\\TH:i:sP') }}"

}
</script>

@endpush
@section('content')
<style>
    .card-title {
  color: black;
  font-weight: bold;
  margin-bottom: 0.5rem;
}
@media screen and (max-width: 600px) {
  .blog_date h4 {
    position: absolute !important;
    left: 7px !important;
    z-index: 1000;
    top: 8px !important;
    margin-top: 0 !important;
    font-size: 12px;
    max-width: 30px;
    padding: 0px !important;
  }
}
.blog__card___area {
  height: 100%;
}
h1 {
  font-size: 1.4em;
  margin: .67em 0;
}
.blogDetailContent div p span img {
  height: 100% !important;
  width: 100% !important;
}
.blogDetailContent div p span {
  width: 100% !important;
  height: 100% !important;
}
h2 span{
    width: 100% !important;
    height: 100% !important;
}
h3 span{
    width: 100% !important;
    height: 100% !important;
}
h4 span{
    width: 100% !important;
    height: 100% !important;
}
h5 span{
    width: 100% !important;
    height: 100% !important;
}
h6 span{
    width: 100% !important;
    height: 100% !important;
}

h1 span{
    width: 100% !important;
    height: 100% !important;
}
.audio-control-btn {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    border-radius: 50px;
    padding: 12px 24px;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    margin-right: 10px;
}

.audio-control-btn:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    color: white;
}

.audio-control-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.audio-player-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.audio-controls {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.progress-container {
    width: 100%;
    background: #e9ecef;
    border-radius: 25px;
    height: 12px;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #007bff, #0056b3, #28a745);
    width: 0%;
    transition: width 0.3s ease;
    border-radius: 25px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    position: relative;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 20px;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3));
    border-radius: 25px;
}

.pulse-animation {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3); }
    50% { box-shadow: 0 4px 25px rgba(0, 123, 255, 0.6); }
    100% { box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3); }
}

.audio-icon {
    font-size: 18px;
    color: #007bff;
    margin-right: 10px;
}

.status-text {
    font-size: 14px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .audio-controls {
        justify-content: center;
        flex-wrap: wrap;
    }
    .audio-control-btn {
        font-size: 14px;
        padding: 10px 20px;
    }
    .audio-player-section {
        padding: 15px;
    }
}
.faq-section {
    background-color: #ffffff;
    padding: 2.5rem 2rem;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    margin-top: 2rem;
}

.faq-header {
    text-align: center;
    margin-bottom: 2.5rem;
    border-bottom: 1px solid #f3f4f6;
    padding-bottom: 2rem;
}

.faq-title {
    color: #111827;
    font-weight: 600;
    font-size: 1.875rem;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.faq-subtitle {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 0;
    font-weight: 400;
}

.professional-accordion {
    max-width: 100%;
}

.faq-item {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 1rem;
    overflow: hidden;
    transition: all 0.2s ease;
}

.faq-item:hover {
    border-color: #d1d5db;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.faq-item:last-child {
    margin-bottom: 0;
}

.faq-question {
    padding: 1.25rem 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: background-color 0.2s ease;
    border: none;
    width: 100%;
    text-align: left;
}

.faq-question:hover {
    background-color: #f9fafb;
}

.faq-question[aria-expanded="true"] {
    background-color: #f3f4f6;
    border-bottom: 1px solid #e5e7eb;
}

.question-content {
    flex: 1;
    display: flex;
    align-items: center;
}

.question-number {
    background-color: #dc3545;
    color: #ffffff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.question-text {
    font-size: 1.125rem;
    font-weight: 500;
    color: #111827;
    margin: 0;
    line-height: 1.4;
}

.question-toggle {
    margin-left: 1rem;
    flex-shrink: 0;
}

.toggle-icon {
    color: #6b7280;
    transition: transform 0.3s ease, color 0.2s ease;
    transform: rotate(0deg);
}

.faq-question[aria-expanded="true"] .toggle-icon {
    transform: rotate(180deg);
    color: #3b82f6;
}

.faq-answer {
    transition: all 0.3s ease;
}

.answer-content {
    padding: 0 1.5rem 1.5rem 1.5rem;
}

.answer-text {
    font-size: 1rem;
    line-height: 1.6;
    color: #4b5563;
    margin: 0;
    padding-top: 0.5rem;
}

/* Animation for collapsing */
.faq-answer.collapsing {
    transition: height 0.3s ease;
}

.faq-answer.show {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .faq-section {
        padding: 2rem 1rem;
        border-radius: 8px;
    }
    
    .faq-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
    }
    
    .faq-title {
        font-size: 1.625rem;
    }
    
    .faq-subtitle {
        font-size: 0.9rem;
    }
    
    .faq-question {
        padding: 1rem 1.25rem;
    }
    
    .question-text {
        font-size: 1rem;
    }
    
    .question-number {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
        margin-right: 0.75rem;
    }
    
    .answer-content {
        padding: 0 1.25rem 1.25rem 1.25rem;
    }
    
    .answer-text {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .faq-section {
        padding: 1.5rem 0.75rem;
    }
    
    .faq-question {
        padding: 0.875rem 1rem;
    }
    
    .answer-content {
        padding: 0 1rem 1rem 1rem;
    }
}

/* Accessibility improvements */
.faq-question:focus {
    outline: 2px solid #3b82f6;
    outline-offset: -2px;
}

.faq-question:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: -2px;
}

/* Better text selection */
.answer-text::selection {
    background-color: #dbeafe;
    color: #1e40af;
}

/* Print styles */
@media print {
    .faq-section {
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .faq-question {
        background-color: transparent !important;
    }
    
    .faq-answer {
        display: block !important;
        height: auto !important;
    }
    
    .toggle-icon {
        display: none;
    }
}

/* Reduced motion for accessibility */
@media (prefers-reduced-motion: reduce) {
    .faq-item,
    .faq-question,
    .toggle-icon,
    .faq-answer {
        transition: none;
    }
    
    .faq-answer.show {
        animation: none;
    }
}

/* No FAQ state */
.no-faq-section {
    background-color: #f9fafb;
    padding: 2rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    text-align: center;
    margin-top: 2rem;
}

.no-faq-text {
    color: #6b7280;
    font-size: 1rem;
    margin: 0;
    font-style: italic;
}

@media (max-width: 768px) {
    .no-faq-section {
        padding: 1.5rem;
    }
    
    .no-faq-text {
        font-size: 0.9rem;
    }
}
</style> 
<!-- home section -->
<section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <a href="{{route('frontend.blog')}}">Blog</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">{{$post_detail['title']}}</span>
          </div>
        </div>
      </div>
    </section>


<div class="container mb-5">
    <div class="row mt-4 mb-4">
        <div class="col-md-12">
            <!-- <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="{{route('frontend.blog')}}/" class="text-danger">Blog</a>/ {{$post_detail['title']}}</p> -->
                 <ul class="nav nav-tabs shadow-sm bg-white rounded tab mb-4"> 
                    <li class="active"><button class="tablinks active" onclick="openCity(event, 'Latest')" id="defaultOpen">Latest Blog</button></li>
                    <li><button class="tablinks" onclick="openCity(event, 'Recent')">Recent Blog</button></li>
                </ul>
        </div>
        <div class="col-md-9">
			<div id="Latest" class="tabcontent">
		        <div class="row">
		              <div class="col-12 sectionHeading">
		                <h1>{{$post_detail['title']}}</h1>
		              </div>
		        </div>
                <!-- Audio Player Section -->
                <div class="audio-player-section">
                    <div class="audio-controls">
                        <i class="fas fa-headphones audio-icon"></i>
                        <button id="startBtn" class="audio-control-btn">
                            <i class="fas fa-play"></i> Start Audio
                        </button>
                        <button id="stopBtn" class="audio-control-btn" disabled>
                            <i class="fas fa-stop"></i> Stop
                        </button>
                        <span id="status" class="text-muted status-text"></span>
                    </div>
                    <div class="progress-container" style="display: none;" id="progressContainer">
                        <div class="progress-bar" id="progressBar"></div>
                    </div>
                </div>		            
		        <div class="d-flex align-items-center justify-content-between mb-3">
		              <div><strong>Published:</strong>
		                <time datetime="2018-08-18">{{$post_detail['published_date']}}</time>
		              </div>
                        <div><strong>Modified:</strong>
                        <time datetime="2020-06-20">{{$post_detail['modified_date']}}</time>
                      </div>
		              <div class="shareBlogPost">
		                <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
		                <a class="a2a_button_facebook"></a>
		                <a class="a2a_button_twitter"></a>
		                <a class="a2a_button_linkedin"></a>
		                </div>
		                
		              </div>
		        </div>
		        <div class="row mb-5">
		              <div class="col-12 blogDetailContent text-justify">
		                <div id="articleContent">
		                  {!! $post_detail['description'] !!}
		                </div>
		              </div>
		              <div class="col-12">
		                <ul class="list-inline">
		                  <li class="list-inline-item">TAGS:</li>
		                  @foreach($post_detail['related_tags'] as $tag)
		                    <li class="list-inline-item tagBox m-2"><a href="javascript:void(0);">{{$tag['name']}}</a></li>
		                  @endforeach
		                 
		                </ul>
		              </div>
                        <!-- FAQ Section -->
                          @if(isset($post_detail['faqs']) && is_array($post_detail['faqs']) && count($post_detail['faqs']) > 0)
                          <div class="col-12 mt-5">
                            <div class="faq-section">
                              <div class="faq-header">
                                <h3 class="faq-title">Frequently Asked Questions</h3>
                                <p class="faq-subtitle">Find answers to common questions</p>
                              </div>
                              
                              <div class="professional-accordion" id="faqAccordion">
                                @foreach($post_detail['faqs'] as $index => $faq)
                                <div class="faq-item">
                                  <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}" 
                                       aria-expanded="false" aria-controls="faqCollapse{{ $index }}">
                                    <div class="question-content">
                                      <div class="question-number">{{ sprintf('%02d', $index + 1) }}</div>
                                      <h5 class="question-text">{{ $faq['question'] }}</h5>
                                    </div>
                                    <div class="question-toggle">
                                      <svg class="toggle-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                      </svg>
                                    </div>
                                  </div>
                                  <div id="faqCollapse{{ $index }}" class="collapse faq-answer" data-bs-parent="#faqAccordion">
                                    <div class="answer-content">
                                      <p class="answer-text">{{ $faq['answer'] }}</p>
                                    </div>
                                  </div>
                                </div>
                                @endforeach
                              </div>
                            </div>
                          </div>
                          @else
                          <div class="col-12 mt-5">
                            <div class="no-faq-section">
                              <p class="no-faq-text">No frequently asked questions available for this article.</p>
                            </div>
                          </div>
                          @endif
                        <!-- End FAQ Section -->
		        </div>
			</div>
			<div id="Recent" class="tabcontent">
                <h3>Recent Blog</h3>
			    <div class="row">                   
                    @foreach ($recentPost as $key=> $recentPost) 
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="{{$recentPost['image']}}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="blog_date text-center">
                                            <h4>{{date('d M',strtotime($recentPost['published_date']))}}</h4>
                                        </div>
                                    </div>
                                </div>
                                <p>Travel <img src="{{asset('assets/images/icons/Rectangle19436.png')}}">  Admin <img src="{{asset('assets/images/icons/Rectangle19436.png')}}"> Coments (8)</p>
                                <h6 class="card-title">{{$recentPost['title']}}</h6>
                                <p class="card-text"> {{ Str::limit($recentPost['short_description'], 100, '...') }}</p>
                                <a href="{{url('blog')}}/{{$recentPost['slug']}}/" target="_blank" class="btn btn-danger">Read More</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
			</div>                 
       </div>
       <div class="col-md-3 mt-2">
       	 @include('frontend.common.bookwithconfidence')
       </div>
    </div>

</div>


<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
document.getElementById("defaultOpen").click();
</script>
<script>
class MinimalAudioPlayer {
    constructor() {
        this.synthesis = window.speechSynthesis;
        this.utterance = null;
        this.isPlaying = false;
        this.indianFemaleVoice = null;
        this.currentPosition = 0;
        this.totalWords = 0;
        
        this.initializeElements();
        this.loadIndianFemaleVoice();
        this.setupEventListeners();
        this.checkBrowserSupport();
    }
    
    initializeElements() {
        this.startBtn = document.getElementById('startBtn');
        this.stopBtn = document.getElementById('stopBtn');
        this.status = document.getElementById('status');
        this.progressContainer = document.getElementById('progressContainer');
        this.progressBar = document.getElementById('progressBar');
        this.articleContent = document.getElementById('articleContent');
    }
    
    checkBrowserSupport() {
        if (!('speechSynthesis' in window)) {
            this.startBtn.disabled = true;
            this.status.textContent = 'Audio not supported in this browser';
            this.status.className = 'text-danger status-text';
            return false;
        }
        return true;
    }
    
    loadIndianFemaleVoice() {
        const loadVoiceHandler = () => {
            const voices = this.synthesis.getVoices();
            console.log('Available voices:', voices.map(v => `${v.name} (${v.lang})`));
            
            // Get all female voices first (very strict filtering)
            const femaleVoices = voices.filter(voice => {
                const voiceName = voice.name.toLowerCase();
                
                // Immediately exclude if explicitly male
                if (voiceName.includes('male') || 
                    voiceName.includes(' man') || 
                    voiceName.includes('boy') ||
                    voiceName.includes('guy') ||
                    voiceName.includes('masculine')) {
                    return false;
                }
                
                // Only include if explicitly female or has female name patterns
                return voiceName.includes('female') ||
                       voiceName.includes('woman') ||
                       voiceName.includes('girl') ||
                       voiceName.includes('lady') ||
                       voiceName.includes('priya') ||
                       voiceName.includes('kavya') ||
                       voiceName.includes('shruti') ||
                       voiceName.includes('lekha') ||
                       voiceName.includes('raveena') ||
                       voiceName.includes('aditi') ||
                       voiceName.includes('veena') ||
                       voiceName.includes('meera') ||
                       voiceName.includes('anita') ||
                       voiceName.includes('sunita') ||
                       voiceName.includes('kiran') ||
                       voiceName.includes('heera') ||
                       voiceName.includes('zira') ||
                       voiceName.includes('aria') ||
                       voiceName.includes('eva') ||
                       voiceName.includes('samantha') ||
                       voiceName.includes('susan') ||
                       voiceName.includes('hazel') ||
                       voiceName.includes('moira') ||
                       voiceName.includes('tessa') ||
                       voiceName.includes('alex') && voiceName.includes('female') ||
                       voiceName.includes('siri');
            });
            
            console.log('Female voices found:', femaleVoices.map(v => `${v.name} (${v.lang})`));
            
            // First priority: Indian language female voices
            this.indianFemaleVoice = femaleVoices.find(voice => {
                const voiceLang = voice.lang.toLowerCase();
                return voiceLang.includes('hi') || 
                       voiceLang.includes('en-in') ||
                       voiceLang.includes('ta') || 
                       voiceLang.includes('te') ||
                       voiceLang.includes('bn') || 
                       voiceLang.includes('gu') ||
                       voiceLang.includes('ml') || 
                       voiceLang.includes('mr') ||
                       voiceLang.includes('kn') || 
                       voiceLang.includes('or') ||
                       voiceLang.includes('pa') || 
                       voiceLang.includes('as') ||
                       voiceLang.includes('ur');
            });
            
            // Second priority: Any English female voice
            if (!this.indianFemaleVoice) {
                this.indianFemaleVoice = femaleVoices.find(voice => {
                    return voice.lang.toLowerCase().includes('en');
                });
            }
            
            // Last resort: Any female voice
            if (!this.indianFemaleVoice && femaleVoices.length > 0) {
                this.indianFemaleVoice = femaleVoices[0];
            }
            
            console.log('Selected voice:', this.indianFemaleVoice ? `${this.indianFemaleVoice.name} (${this.indianFemaleVoice.lang})` : 'No female voice found');
        };
        
        loadVoiceHandler();
        
        if (this.synthesis.onvoiceschanged !== undefined) {
            this.synthesis.onvoiceschanged = loadVoiceHandler;
        }
        
        setTimeout(loadVoiceHandler, 1000);
    }
    
    setupEventListeners() {
        if (!this.startBtn || !this.stopBtn) return;
        
        this.startBtn.addEventListener('click', () => this.start());
        this.stopBtn.addEventListener('click', () => this.stop());
    }
    
    getTextContent() {
        if (!this.articleContent) return '';
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = this.articleContent.innerHTML;
        return tempDiv.textContent || tempDiv.innerText || '';
    }
    
    createUtterance(text) {
        this.utterance = new SpeechSynthesisUtterance(text);
        
        // Set the Indian female voice if available
        if (this.indianFemaleVoice) {
            this.utterance.voice = this.indianFemaleVoice;
        }
        
        // Optimized settings for Indian female voice
        this.utterance.rate = 0.85;
        this.utterance.pitch = 1.1;
        this.utterance.volume = 1;
        
        this.utterance.onstart = () => {
            this.isPlaying = true;
            this.updateUI();
            this.status.textContent = 'Playing...';
            this.status.className = 'text-success status-text';
            this.startBtn.classList.add('pulse-animation');
            this.progressContainer.style.display = 'block';
        };
        
        this.utterance.onend = () => {
            this.isPlaying = false;
            this.currentPosition = 0;
            this.updateUI();
            this.status.textContent = 'Audio completed';
            this.status.className = 'text-muted status-text';
            this.startBtn.classList.remove('pulse-animation');
            setTimeout(() => {
                this.progressContainer.style.display = 'none';
                this.progressBar.style.width = '0%';
            }, 2000);
        };
        
        this.utterance.onerror = (event) => {
            this.status.textContent = `Error: ${event.error}`;
            this.status.className = 'text-danger status-text';
            this.isPlaying = false;
            this.updateUI();
            this.startBtn.classList.remove('pulse-animation');
            this.progressContainer.style.display = 'none';
        };
        
        this.utterance.onboundary = (event) => {
            if (event.name === 'word') {
                this.currentPosition++;
                const progress = Math.min((this.currentPosition / this.totalWords) * 100, 100);
                this.progressBar.style.width = `${progress}%`;
            }
        };
        
        return this.utterance;
    }
    
    start() {
        const textContent = this.getTextContent();
        
        if (!textContent.trim()) {
            this.status.textContent = 'No content to read';
            this.status.className = 'text-warning status-text';
            return;
        }
        
        this.synthesis.cancel();
        this.totalWords = textContent.split(/\s+/).length;
        this.currentPosition = 0;
        
        const utterance = this.createUtterance(textContent);
        this.synthesis.speak(utterance);
    }
    
    stop() {
        this.synthesis.cancel();
        this.isPlaying = false;
        this.currentPosition = 0;
        this.updateUI();
        this.status.textContent = 'Audio stopped';
        this.status.className = 'text-muted status-text';
        this.startBtn.classList.remove('pulse-animation');
        this.progressContainer.style.display = 'none';
        this.progressBar.style.width = '0%';
    }
    
    updateUI() {
        if (!this.startBtn || !this.stopBtn) return;
        
        if (!this.isPlaying) {
            this.startBtn.innerHTML = '<i class="fas fa-play"></i> Start Audio';
            this.startBtn.disabled = false;
            this.stopBtn.disabled = true;
        } else {
            this.startBtn.innerHTML = '<i class="fas fa-volume-up"></i> Playing...';
            this.startBtn.disabled = true;
            this.stopBtn.disabled = false;
        }
    }
}

// Initialize the audio player when the page loads
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('articleContent')) {
        new MinimalAudioPlayer();
    }
});

// Handle tab switching - stop audio when switching tabs
function openCity(evt, cityName) {
    // Your existing tab switching code here
    
    // Stop audio when switching tabs
    if (window.speechSynthesis) {
        window.speechSynthesis.cancel();
    }
    
    // Reset audio player UI if on different tab
    const startBtn = document.getElementById('startBtn');
    const stopBtn = document.getElementById('stopBtn');
    const status = document.getElementById('status');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    
    if (startBtn && cityName !== 'Latest') {
        startBtn.innerHTML = '<i class="fas fa-play"></i> Start Audio';
        startBtn.disabled = false;
        startBtn.classList.remove('pulse-animation');
        if (stopBtn) stopBtn.disabled = true;
        if (status) {
            status.textContent = '';
            status.className = 'text-muted status-text';
        }
        if (progressContainer) progressContainer.style.display = 'none';
        if (progressBar) progressBar.style.width = '0%';
    }
}
</script>
@endsection