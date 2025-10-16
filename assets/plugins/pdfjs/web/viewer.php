<?php

$ourl="";
$fname="";
//echo @trim($_GET["ofile"]);
if(isset($_GET) and isset($_GET["ofile"]) and !empty(@trim($_GET["ofile"])) and isset($_GET["fname"]) and !empty(@trim($_GET["fname"]))){
  if(isset($_GET) and isset($_GET["file"]) and !empty(@trim($_GET["file"]))){
    $furl=urldecode(@trim($_GET["file"]));
    if(preg_match("/([^\*]+\/)[^\?\/]+\?/s",$furl,$tmpurl)){
      $ourl=$tmpurl[1].@trim($_GET["fname"])."?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=".urldecode(@trim($_GET["ofile"]));
      $tmpurl=null;
	  if(substr_count(strtolower($ourl), strtolower("http")) > 1){
		 if(substr_count(strtolower($_GET["file"]), strtolower("http")) > 0){
			$ourl=$_GET["file"];
		 }elseif(substr_count(strtolower($_GET["ofile"]), strtolower("http")) > 0){
			 $ourl=$_GET["ofile"];
		 } 
	  }
    }
  }
  if(!empty($_GET["fname"])){
	  if(substr_count(strtolower($_GET["fname"]), strtolower("http")) == 0){ 
		$fname=$_GET["fname"];
	  }
  }
  //$fname="test.pdf";
}

if(isset($_GET) and isset($_GET["file"]) and !empty(@trim($_GET["file"])) and !isset($_GET["ofile"])){
	$ourl=$_GET["file"];
}
$ourl=addslashes($ourl);
if(empty($fname)) $fname="download.pdf";
?>
<!DOCTYPE html>
<!--
Copyright 2012 Mozilla Foundation

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Adobe CMap resources are covered by their own copyright but the same license:

    Copyright 1990-2015 Adobe Systems Incorporated.

See https://github.com/adobe-type-tools/cmap-resources
-->
<html dir="ltr" mozdisallowselectionprint>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!--#if GENERIC || CHROME-->
    <meta name="google" content="notranslate">
<!--#endif-->
    <title>PDF.js viewer</title>

<!--#if MOZCENTRAL-->
<!--#include viewer-snippet-firefox-extension.html-->
<!--#endif-->
<!--#if CHROME-->
<!--#include viewer-snippet-chrome-extension.html-->
<!--#endif-->

    <link rel="stylesheet" href="viewer.css">
<!--#if !PRODUCTION-->
    <link rel="resource" type="application/l10n" href="locale/locale.properties">
<!--#endif-->

<!--#if !PRODUCTION-->
    <!--<script defer src="../node_modules/es-module-shims/dist/es-module-shims.js"></script>-->
    <script async src="https://unpkg.com/es-module-shims@1.3.0/dist/es-module-shims.js"></script>

    <script type="importmap-shim">
      {
        "imports": {
          "pdfjs/": "../src/",
          "pdfjs-lib": "../src/pdf.js",
          "pdfjs-web/": "./"
        }
      }
    </script>

    <!--<script src="viewer.js" type="module-shim"></script>-->
    <script  type="module-shim">

    import { AppOptions } from "./app_options.js";
    import { PDFViewerApplication } from "./app.js";

    /* eslint-disable-next-line no-unused-vars */
    const pdfjsVersion =
      typeof PDFJSDev !== "undefined" ? PDFJSDev.eval("BUNDLE_VERSION") : void 0;
    /* eslint-disable-next-line no-unused-vars */
    const pdfjsBuild =
      typeof PDFJSDev !== "undefined" ? PDFJSDev.eval("BUNDLE_BUILD") : void 0;

    window.PDFViewerApplication = PDFViewerApplication;
    window.PDFViewerApplicationOptions = AppOptions;

    if (typeof PDFJSDev !== "undefined" && PDFJSDev.test("CHROME")) {
      var defaultUrl; // eslint-disable-line no-var

      (function rewriteUrlClosure() {
        // Run this code outside DOMContentLoaded to make sure that the URL
        // is rewritten as soon as possible.
        const queryString = document.location.search.slice(1);
        const m = /(^|&)file=([^&]*)/.exec(queryString);
        defaultUrl = m ? decodeURIComponent(m[2]) : "";
        // Example: chrome-extension://.../http://example.com/file.pdf
        const humanReadableUrl = "/" + defaultUrl + location.hash;
        history.replaceState(history.state, "", humanReadableUrl);
        if (top === window) {
          // eslint-disable-next-line no-undef
          chrome.runtime.sendMessage("showPageAction");
        }
      })();
    }

    if (typeof PDFJSDev !== "undefined" && PDFJSDev.test("MOZCENTRAL")) {
      require("./firefoxcom.js");
      require("./firefox_print_service.js");
    }
    if (typeof PDFJSDev !== "undefined" && PDFJSDev.test("GENERIC")) {
      require("./genericcom.js");
    }
    if (typeof PDFJSDev !== "undefined" && PDFJSDev.test("CHROME")) {
      require("./chromecom.js");
    }
    if (typeof PDFJSDev !== "undefined" && PDFJSDev.test("CHROME || GENERIC")) {
      require("./pdf_print_service.js");
    }

    function getViewerConfiguration() {
      let errorWrapper = null;
      if (typeof PDFJSDev === "undefined" || !PDFJSDev.test("MOZCENTRAL")) {
        errorWrapper = {
          container: document.getElementById("errorWrapper"),
          errorMessage: document.getElementById("errorMessage"),
          closeButton: document.getElementById("errorClose"),
          errorMoreInfo: document.getElementById("errorMoreInfo"),
          moreInfoButton: document.getElementById("errorShowMore"),
          lessInfoButton: document.getElementById("errorShowLess"),
        };
      }

      return {
        appContainer: document.body,
        mainContainer: document.getElementById("viewerContainer"),
        viewerContainer: document.getElementById("viewer"),
        eventBus: null,
        toolbar: {
          container: document.getElementById("toolbarViewer"),
          numPages: document.getElementById("numPages"),
          pageNumber: document.getElementById("pageNumber"),
          scaleSelect: document.getElementById("scaleSelect"),
          customScaleOption: document.getElementById("customScaleOption"),
          previous: document.getElementById("previous"),
          next: document.getElementById("next"),
          zoomIn: document.getElementById("zoomIn"),
          zoomOut: document.getElementById("zoomOut"),
          viewFind: document.getElementById("viewFind"),
          openFile: document.getElementById("openFile"),
          print: document.getElementById("print"),
          presentationModeButton: document.getElementById("presentationMode"),
          download: document.getElementById("download"),
          viewBookmark: document.getElementById("viewBookmark"),
        },
        secondaryToolbar: {
          toolbar: document.getElementById("secondaryToolbar"),
          toggleButton: document.getElementById("secondaryToolbarToggle"),
          toolbarButtonContainer: document.getElementById(
            "secondaryToolbarButtonContainer"
          ),
          presentationModeButton: document.getElementById(
            "secondaryPresentationMode"
          ),
          openFileButton: document.getElementById("secondaryOpenFile"),
          printButton: document.getElementById("secondaryPrint"),
          downloadButton: document.getElementById("secondaryDownload"),
          viewBookmarkButton: document.getElementById("secondaryViewBookmark"),
          firstPageButton: document.getElementById("firstPage"),
          lastPageButton: document.getElementById("lastPage"),
          pageRotateCwButton: document.getElementById("pageRotateCw"),
          pageRotateCcwButton: document.getElementById("pageRotateCcw"),
          cursorSelectToolButton: document.getElementById("cursorSelectTool"),
          cursorHandToolButton: document.getElementById("cursorHandTool"),
          scrollPageButton: document.getElementById("scrollPage"),
          scrollVerticalButton: document.getElementById("scrollVertical"),
          scrollHorizontalButton: document.getElementById("scrollHorizontal"),
          scrollWrappedButton: document.getElementById("scrollWrapped"),
          spreadNoneButton: document.getElementById("spreadNone"),
          spreadOddButton: document.getElementById("spreadOdd"),
          spreadEvenButton: document.getElementById("spreadEven"),
          documentPropertiesButton: document.getElementById("documentProperties"),
        },
        sidebar: {
          // Divs (and sidebar button)
          outerContainer: document.getElementById("outerContainer"),
          viewerContainer: document.getElementById("viewerContainer"),
          toggleButton: document.getElementById("sidebarToggle"),
          // Buttons
          thumbnailButton: document.getElementById("viewThumbnail"),
          outlineButton: document.getElementById("viewOutline"),
          attachmentsButton: document.getElementById("viewAttachments"),
          layersButton: document.getElementById("viewLayers"),
          // Views
          thumbnailView: document.getElementById("thumbnailView"),
          outlineView: document.getElementById("outlineView"),
          attachmentsView: document.getElementById("attachmentsView"),
          layersView: document.getElementById("layersView"),
          // View-specific options
          outlineOptionsContainer: document.getElementById(
            "outlineOptionsContainer"
          ),
          currentOutlineItemButton: document.getElementById("currentOutlineItem"),
        },
        sidebarResizer: {
          outerContainer: document.getElementById("outerContainer"),
          resizer: document.getElementById("sidebarResizer"),
        },
        findBar: {
          bar: document.getElementById("findbar"),
          toggleButton: document.getElementById("viewFind"),
          findField: document.getElementById("findInput"),
          highlightAllCheckbox: document.getElementById("findHighlightAll"),
          caseSensitiveCheckbox: document.getElementById("findMatchCase"),
          entireWordCheckbox: document.getElementById("findEntireWord"),
          findMsg: document.getElementById("findMsg"),
          findResultsCount: document.getElementById("findResultsCount"),
          findPreviousButton: document.getElementById("findPrevious"),
          findNextButton: document.getElementById("findNext"),
        },
        passwordOverlay: {
          overlayName: "passwordOverlay",
          container: document.getElementById("passwordOverlay"),
          label: document.getElementById("passwordText"),
          input: document.getElementById("password"),
          submitButton: document.getElementById("passwordSubmit"),
          cancelButton: document.getElementById("passwordCancel"),
        },
        documentProperties: {
          overlayName: "documentPropertiesOverlay",
          container: document.getElementById("documentPropertiesOverlay"),
          closeButton: document.getElementById("documentPropertiesClose"),
          fields: {
            fileName: document.getElementById("fileNameField"),
            fileSize: document.getElementById("fileSizeField"),
            title: document.getElementById("titleField"),
            author: document.getElementById("authorField"),
            subject: document.getElementById("subjectField"),
            keywords: document.getElementById("keywordsField"),
            creationDate: document.getElementById("creationDateField"),
            modificationDate: document.getElementById("modificationDateField"),
            creator: document.getElementById("creatorField"),
            producer: document.getElementById("producerField"),
            version: document.getElementById("versionField"),
            pageCount: document.getElementById("pageCountField"),
            pageSize: document.getElementById("pageSizeField"),
            linearized: document.getElementById("linearizedField"),
          },
        },
        errorWrapper,
        printContainer: document.getElementById("printContainer"),
        openFileInputName: "fileInput",
        debuggerScriptPath: "./debugger.js",
      };
    }

    function webViewerLoad() {
      const config = getViewerConfiguration();
      if (typeof PDFJSDev === "undefined" || !PDFJSDev.test("PRODUCTION")) {
        Promise.all([
          import("pdfjs-web/genericcom.js"),
          import("pdfjs-web/pdf_print_service.js"),
        ]).then(function ([genericCom, pdfPrintService]) {
          PDFViewerApplication.run(config);
        });
      } else {
        if (typeof PDFJSDev !== "undefined" && PDFJSDev.test("CHROME")) {
          AppOptions.set("defaultUrl", defaultUrl);
        }

        if (typeof PDFJSDev !== "undefined" && PDFJSDev.test("GENERIC")) {
          // Give custom implementations of the default viewer a simpler way to
          // set various `AppOptions`, by dispatching an event once all viewer
          // files are loaded but *before* the viewer initialization has run.
          const event = document.createEvent("CustomEvent");
          event.initCustomEvent("webviewerloaded", true, true, {
            source: window,
          });
          try {
            // Attempt to dispatch the event at the embedding `document`,
            // in order to support cases where the viewer is embedded in
            // a *dynamically* created <iframe> element.
            parent.document.dispatchEvent(event);
          } catch (ex) {
            // The viewer could be in e.g. a cross-origin <iframe> element,
            // fallback to dispatching the event at the current `document`.
            console.error(`webviewerloaded: ${ex}`);
            document.dispatchEvent(event);
          }
        }

        PDFViewerApplication.run(config);
      }
    }

    // Block the "load" event until all pages are loaded, to ensure that printing
    // works in Firefox; see https://bugzilla.mozilla.org/show_bug.cgi?id=1618553
    if (document.blockUnblockOnload) {
      document.blockUnblockOnload(true);
    }

    if (
      document.readyState === "interactive" ||
      document.readyState === "complete"
    ) {
      webViewerLoad();
    } else {
      document.addEventListener("DOMContentLoaded", webViewerLoad, true);
    }

    export { PDFViewerApplication, AppOptions as PDFViewerApplicationOptions };

    </script>
<!--#endif-->

<!--#if (GENERIC && !MINIFIED) -->
<!--#include viewer-snippet.html-->
<!--#endif-->

<!--#if !MINIFIED -->
<!--<script src="viewer.js"></script>-->
<!--#else-->
<!--#include viewer-snippet-minified.html-->
<!--#endif-->

  </head>

  <body tabindex="1">
    <div id="outerContainer">

      <div id="sidebarContainer">
        <div id="toolbarSidebar">
          <div id="toolbarSidebarLeft">
            <div class="splitToolbarButton toggled">
              <button id="viewThumbnail" class="toolbarButton toggled" title="Show Thumbnails" tabindex="2" data-l10n-id="thumbs">
                 <span data-l10n-id="thumbs_label">Thumbnails</span>
              </button>
              <button id="viewOutline" class="toolbarButton" title="Show Document Outline (double-click to expand/collapse all items)" tabindex="3" data-l10n-id="document_outline">
                 <span data-l10n-id="document_outline_label">Document Outline</span>
              </button>
              <button id="viewAttachments" class="toolbarButton" title="Show Attachments" tabindex="4" data-l10n-id="attachments">
                 <span data-l10n-id="attachments_label">Attachments</span>
              </button>
              <button id="viewLayers" class="toolbarButton" title="Show Layers (double-click to reset all layers to the default state)" tabindex="5" data-l10n-id="layers">
                 <span data-l10n-id="layers_label">Layers</span>
              </button>
            </div>
          </div>

          <div id="toolbarSidebarRight">
            <div id="outlineOptionsContainer" class="hidden">
              <div class="verticalToolbarSeparator"></div>

              <button id="currentOutlineItem" class="toolbarButton" disabled="disabled" title="Find Current Outline Item" tabindex="6" data-l10n-id="current_outline_item">
                <span data-l10n-id="current_outline_item_label">Current Outline Item</span>
              </button>
            </div>
          </div>
        </div>
        <div id="sidebarContent">
          <div id="thumbnailView">
          </div>
          <div id="outlineView" class="hidden">
          </div>
          <div id="attachmentsView" class="hidden">
          </div>
          <div id="layersView" class="hidden">
          </div>
        </div>
        <div id="sidebarResizer"></div>
      </div>  <!-- sidebarContainer -->

      <div id="mainContainer">
        <div class="findbar hidden doorHanger" id="findbar">
          <div id="findbarInputContainer">
            <input id="findInput" class="toolbarField" title="Find" placeholder="Find in document…" tabindex="91" data-l10n-id="find_input">
            <div class="splitToolbarButton">
              <button id="findPrevious" class="toolbarButton findPrevious" title="Find the previous occurrence of the phrase" tabindex="92" data-l10n-id="find_previous">
                <span data-l10n-id="find_previous_label">Previous</span>
              </button>
              <div class="splitToolbarButtonSeparator"></div>
              <button id="findNext" class="toolbarButton findNext" title="Find the next occurrence of the phrase" tabindex="93" data-l10n-id="find_next">
                <span data-l10n-id="find_next_label">Next</span>
              </button>
            </div>
          </div>

          <div id="findbarOptionsOneContainer">
            <input type="checkbox" id="findHighlightAll" class="toolbarField" tabindex="94">
            <label for="findHighlightAll" class="toolbarLabel" data-l10n-id="find_highlight">Highlight all</label>
            <input type="checkbox" id="findMatchCase" class="toolbarField" tabindex="95">
            <label for="findMatchCase" class="toolbarLabel" data-l10n-id="find_match_case_label">Match case</label>
          </div>
          <div id="findbarOptionsTwoContainer">
            <input type="checkbox" id="findEntireWord" class="toolbarField" tabindex="96">
            <label for="findEntireWord" class="toolbarLabel" data-l10n-id="find_entire_word_label">Whole words</label>
            <span id="findResultsCount" class="toolbarLabel hidden"></span>
          </div>

          <div id="findbarMessageContainer">
            <span id="findMsg" class="toolbarLabel"></span>
          </div>
        </div>  <!-- findbar -->

        <div id="secondaryToolbar" class="secondaryToolbar hidden doorHangerRight">
          <div id="secondaryToolbarButtonContainer">
            <button id="secondaryPresentationMode" class="secondaryToolbarButton presentationMode visibleLargeView" title="Switch to Presentation Mode" tabindex="51" data-l10n-id="presentation_mode">
              <span data-l10n-id="presentation_mode_label">Presentation Mode</span>
            </button>

            <button id="secondaryOpenFile" class="secondaryToolbarButton openFile visibleLargeView" title="Open File" tabindex="52" data-l10n-id="open_file">
              <span data-l10n-id="open_file_label">Open</span>
            </button>

            <button id="secondaryPrint" class="secondaryToolbarButton print visibleMediumView" title="Print" tabindex="53" data-l10n-id="print">
              <span data-l10n-id="print_label">Print</span>
            </button>

            <button id="secondaryDownload" class="secondaryToolbarButton download visibleMediumView" title="Download" tabindex="54" data-l10n-id="download">
              <span data-l10n-id="download_label">Download</span>
            </button>

            <a href="#" id="secondaryViewBookmark" class="secondaryToolbarButton bookmark visibleSmallView" title="Current view (copy or open in new window)" tabindex="55" data-l10n-id="bookmark">
              <span data-l10n-id="bookmark_label">Current View</span>
            </a>

            <div class="horizontalToolbarSeparator visibleLargeView"></div>

            <button id="firstPage" class="secondaryToolbarButton firstPage" title="Go to First Page" tabindex="56" data-l10n-id="first_page">
              <span data-l10n-id="first_page_label">Go to First Page</span>
            </button>
            <button id="lastPage" class="secondaryToolbarButton lastPage" title="Go to Last Page" tabindex="57" data-l10n-id="last_page">
              <span data-l10n-id="last_page_label">Go to Last Page</span>
            </button>

            <div class="horizontalToolbarSeparator"></div>

            <button id="pageRotateCw" class="secondaryToolbarButton rotateCw" title="Rotate Clockwise" tabindex="58" data-l10n-id="page_rotate_cw">
              <span data-l10n-id="page_rotate_cw_label">Rotate Clockwise</span>
            </button>
            <button id="pageRotateCcw" class="secondaryToolbarButton rotateCcw" title="Rotate Counterclockwise" tabindex="59" data-l10n-id="page_rotate_ccw">
              <span data-l10n-id="page_rotate_ccw_label">Rotate Counterclockwise</span>
            </button>

            <div class="horizontalToolbarSeparator"></div>

            <button id="cursorSelectTool" class="secondaryToolbarButton selectTool toggled" title="Enable Text Selection Tool" tabindex="60" data-l10n-id="cursor_text_select_tool">
              <span data-l10n-id="cursor_text_select_tool_label">Text Selection Tool</span>
            </button>
            <button id="cursorHandTool" class="secondaryToolbarButton handTool" title="Enable Hand Tool" tabindex="61" data-l10n-id="cursor_hand_tool">
              <span data-l10n-id="cursor_hand_tool_label">Hand Tool</span>
            </button>

            <div class="horizontalToolbarSeparator"></div>

            <button id="scrollPage" class="secondaryToolbarButton scrollModeButtons scrollPage" title="Use Page Scrolling" tabindex="62" data-l10n-id="scroll_page">
              <span data-l10n-id="scroll_page_label">Page Scrolling</span>
            </button>
            <button id="scrollVertical" class="secondaryToolbarButton scrollModeButtons scrollVertical toggled" title="Use Vertical Scrolling" tabindex="63" data-l10n-id="scroll_vertical">
              <span data-l10n-id="scroll_vertical_label">Vertical Scrolling</span>
            </button>
            <button id="scrollHorizontal" class="secondaryToolbarButton scrollModeButtons scrollHorizontal" title="Use Horizontal Scrolling" tabindex="64" data-l10n-id="scroll_horizontal">
              <span data-l10n-id="scroll_horizontal_label">Horizontal Scrolling</span>
            </button>
            <button id="scrollWrapped" class="secondaryToolbarButton scrollModeButtons scrollWrapped" title="Use Wrapped Scrolling" tabindex="65" data-l10n-id="scroll_wrapped">
              <span data-l10n-id="scroll_wrapped_label">Wrapped Scrolling</span>
            </button>

            <div class="horizontalToolbarSeparator scrollModeButtons"></div>

            <button id="spreadNone" class="secondaryToolbarButton spreadModeButtons spreadNone toggled" title="Do not join page spreads" tabindex="66" data-l10n-id="spread_none">
              <span data-l10n-id="spread_none_label">No Spreads</span>
            </button>
            <button id="spreadOdd" class="secondaryToolbarButton spreadModeButtons spreadOdd" title="Join page spreads starting with odd-numbered pages" tabindex="67" data-l10n-id="spread_odd">
              <span data-l10n-id="spread_odd_label">Odd Spreads</span>
            </button>
            <button id="spreadEven" class="secondaryToolbarButton spreadModeButtons spreadEven" title="Join page spreads starting with even-numbered pages" tabindex="68" data-l10n-id="spread_even">
              <span data-l10n-id="spread_even_label">Even Spreads</span>
            </button>

            <div class="horizontalToolbarSeparator spreadModeButtons"></div>

            <button style="display:none;" id="documentProperties" class="secondaryToolbarButton documentProperties" title="Document Properties…" tabindex="69" data-l10n-id="document_properties">
              <span data-l10n-id="document_properties_label">Document Properties…</span>
            </button>
          </div>
        </div>  <!-- secondaryToolbar -->

        <div class="toolbar">
          <div id="toolbarContainer">
            <div id="toolbarViewer">
              <div id="toolbarViewerLeft">
                <button id="sidebarToggle" class="toolbarButton" title="Toggle Sidebar" tabindex="11" data-l10n-id="toggle_sidebar" aria-expanded="false" aria-controls="sidebarContainer">
                  <span data-l10n-id="toggle_sidebar_label">Toggle Sidebar</span>
                </button>
                <div class="toolbarButtonSpacer"></div>
                <button id="viewFind" class="toolbarButton" title="Find in Document" tabindex="12" data-l10n-id="findbar" aria-expanded="false" aria-controls="findbar">
                  <span data-l10n-id="findbar_label">Find</span>
                </button>
                <div class="splitToolbarButton hiddenSmallView">
                  <button class="toolbarButton pageUp" title="Previous Page" id="previous" tabindex="13" data-l10n-id="previous">
                    <span data-l10n-id="previous_label">Previous</span>
                  </button>
                  <div class="splitToolbarButtonSeparator"></div>
                  <button class="toolbarButton pageDown" title="Next Page" id="next" tabindex="14" data-l10n-id="next">
                    <span data-l10n-id="next_label">Next</span>
                  </button>
                </div>
                <input type="number" id="pageNumber" class="toolbarField pageNumber" title="Page" value="1" size="4" min="1" tabindex="15" data-l10n-id="page" autocomplete="off">
                <span id="numPages" class="toolbarLabel"></span>
              </div>
              <div id="toolbarViewerRight">
                <button id="presentationMode" class="toolbarButton presentationMode hiddenLargeView" title="Switch to Presentation Mode" tabindex="31" data-l10n-id="presentation_mode">
                  <span data-l10n-id="presentation_mode_label">Presentation Mode</span>
                </button>

                <button style="display:none;" id="openFile" class="toolbarButton openFile hiddenLargeView" title="Open File" tabindex="32" data-l10n-id="open_file">
                  <span data-l10n-id="open_file_label">Open</span>
                </button>

                <button id="print" class="toolbarButton print hiddenMediumView" title="Print" tabindex="33" data-l10n-id="print">
                  <span data-l10n-id="print_label">Print</span>
                </button>

                <button style="display:none;" id="download" class="toolbarButton download hiddenMediumView" title="Download" tabindex="34" data-l10n-id="download">
                  <span data-l10n-id="download_label">Download</span>
                </button>
                <button id="downloads" class="toolbarButton download hiddenMediumView" title="Download" tabindex="34" data-l10n-id="download" onclick="forceDownload('<?php echo $ourl; ?>','<?php echo $fname; ?>')"> 
                  <span data-l10n-id="download_label">Download</span>
                </button>
                <a href="#" id="viewBookmark" class="toolbarButton bookmark hiddenSmallView" title="Current view (copy or open in new window)" tabindex="35" data-l10n-id="bookmark">
                  <span data-l10n-id="bookmark_label">Current View</span>
                </a>

                <div class="verticalToolbarSeparator hiddenSmallView"></div>

                <button id="secondaryToolbarToggle" class="toolbarButton" title="Tools" tabindex="36" data-l10n-id="tools" aria-expanded="false" aria-controls="secondaryToolbar">
                  <span data-l10n-id="tools_label">Tools</span>
                </button>
              </div>
              <div id="toolbarViewerMiddle">
                <div class="splitToolbarButton">
                  <button id="zoomOut" class="toolbarButton zoomOut" title="Zoom Out" tabindex="21" data-l10n-id="zoom_out">
                    <span data-l10n-id="zoom_out_label">Zoom Out</span>
                  </button>
                  <div class="splitToolbarButtonSeparator"></div>
                  <button id="zoomIn" class="toolbarButton zoomIn" title="Zoom In" tabindex="22" data-l10n-id="zoom_in">
                    <span data-l10n-id="zoom_in_label">Zoom In</span>
                   </button>
                </div>
                <span id="scaleSelectContainer" class="dropdownToolbarButton">
                  <select id="scaleSelect" title="Zoom" tabindex="23" data-l10n-id="zoom">
                    <option id="pageAutoOption" title="" value="auto" selected="selected" data-l10n-id="page_scale_auto">Automatic Zoom</option>
                    <option id="pageActualOption" title="" value="page-actual" data-l10n-id="page_scale_actual">Actual Size</option>
                    <option id="pageFitOption" title="" value="page-fit" data-l10n-id="page_scale_fit">Page Fit</option>
                    <option id="pageWidthOption" title="" value="page-width" data-l10n-id="page_scale_width">Page Width</option>
                    <option id="customScaleOption" title="" value="custom" disabled="disabled" hidden="true"></option>
                    <option title="" value="0.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 50 }'>50%</option>
                    <option title="" value="0.75" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 75 }'>75%</option>
                    <option title="" value="1" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 100 }'>100%</option>
                    <option title="" value="1.25" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 125 }'>125%</option>
                    <option title="" value="1.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 150 }'>150%</option>
                    <option title="" value="2" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 200 }'>200%</option>
                    <option title="" value="3" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 300 }'>300%</option>
                    <option title="" value="4" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 400 }'>400%</option>
                  </select>
                </span>
              </div>
            </div>
            <div id="loadingBar">
              <div class="progress">
                <div class="glimmer">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="viewerContainer" tabindex="0">
          <div id="viewer" class="pdfViewer"></div>
        </div>

<!--#if !MOZCENTRAL-->
        <div id="errorWrapper" hidden='true'>
          <div id="errorMessageLeft">
            <span id="errorMessage"></span>
            <button id="errorShowMore" data-l10n-id="error_more_info">
              More Information
            </button>
            <button id="errorShowLess" data-l10n-id="error_less_info" hidden='true'>
              Less Information
            </button>
          </div>
          <div id="errorMessageRight">
            <button id="errorClose" data-l10n-id="error_close">
              Close
            </button>
          </div>
          <div class="clearBoth"></div>
          <textarea id="errorMoreInfo" hidden='true' readonly="readonly"></textarea>
        </div>
<!--#endif-->
      </div> <!-- mainContainer -->

      <div id="overlayContainer" class="hidden">
        <div id="passwordOverlay" class="container hidden">
          <div class="dialog">
            <div class="row">
              <p id="passwordText" data-l10n-id="password_label">Enter the password to open this PDF file:</p>
            </div>
            <div class="row">
              <input type="password" id="password" class="toolbarField">
            </div>
            <div class="buttonRow">
              <button id="passwordCancel" class="overlayButton"><span data-l10n-id="password_cancel">Cancel</span></button>
              <button id="passwordSubmit" class="overlayButton"><span data-l10n-id="password_ok">OK</span></button>
            </div>
          </div>
        </div>
        <div id="documentPropertiesOverlay" class="container hidden">
          <div class="dialog">
            <div class="row">
              <span data-l10n-id="document_properties_file_name">File name:</span> <p id="fileNameField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_file_size">File size:</span> <p id="fileSizeField">-</p>
            </div>
            <div class="separator"></div>
            <div class="row">
              <span data-l10n-id="document_properties_title">Title:</span> <p id="titleField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_author">Author:</span> <p id="authorField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_subject">Subject:</span> <p id="subjectField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_keywords">Keywords:</span> <p id="keywordsField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_creation_date">Creation Date:</span> <p id="creationDateField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_modification_date">Modification Date:</span> <p id="modificationDateField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_creator">Creator:</span> <p id="creatorField">-</p>
            </div>
            <div class="separator"></div>
            <div class="row">
              <span data-l10n-id="document_properties_producer">PDF Producer:</span> <p id="producerField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_version">PDF Version:</span> <p id="versionField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_page_count">Page Count:</span> <p id="pageCountField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_page_size">Page Size:</span> <p id="pageSizeField">-</p>
            </div>
            <div class="separator"></div>
            <div class="row">
              <span data-l10n-id="document_properties_linearized">Fast Web View:</span> <p id="linearizedField">-</p>
            </div>
            <div class="buttonRow">
              <button id="documentPropertiesClose" class="overlayButton"><span data-l10n-id="document_properties_close">Close</span></button>
            </div>
          </div>
        </div>
<!--#if !MOZCENTRAL-->
        <div id="printServiceOverlay" class="container hidden">
          <div class="dialog">
            <div class="row">
              <span data-l10n-id="print_progress_message">Preparing document for printing…</span>
            </div>
            <div class="row">
              <progress value="0" max="100"></progress>
              <span data-l10n-id="print_progress_percent" data-l10n-args='{ "progress": 0 }' class="relative-progress">0%</span>
            </div>
            <div class="buttonRow">
              <button id="printCancel" class="overlayButton"><span data-l10n-id="print_progress_close">Cancel</span></button>
            </div>
          </div>
        </div>
<!--#endif-->
<!--#if CHROME-->
<!--#include viewer-snippet-chrome-overlays.html-->
<!--#endif-->
      </div>  <!-- overlayContainer -->

    </div> <!-- outerContainer -->
    <div id="printContainer"></div>
    <script>
    function downloads(url) {//alert(url);
      if(url != ""){
      const a = document.createElement('a')
      a.href = url
      a.download = url.split('/').pop()
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
      }
    }
	
	function forceDownload2(url, filename) {
		const a = document.createElement('a');
		a.href = url;
		a.setAttribute('download', filename || '');
		parent.document.body.appendChild(a);
		a.click();
		parent.document.body.removeChild(a);
	}
	
	function forceDownload(url, filename){
		const a = document.createElement('a');
		a.href = url;
		a.download = 'Download.pdf'; // optional (relies on Content-Disposition too)
		document.body.appendChild(a);
		a.click();
		window.close();		
	}
    </script>
  </body>
</html>
