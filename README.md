
<h2 class="code-line" data-line-start="3" data-line-end="4"><a id="Implementing_SMS_drivers_3"></a>Implementing SMS drivers</h2>
<p class="has-line-data" data-line-start="4" data-line-end="7">Suppose, we have 3 SMS provider based on restful API which if necessary, we should be able to switch between them.<br>
You should call a thirty-party API for each driver and pass their relevant parameters.<br>
For example: username, password, and receptor.</p>
<h2 class="code-line" data-line-start="8" data-line-end="9"><a id="Implementing_order_register_8"></a>Implementing order register</h2>
<p class="has-line-data" data-line-start="9" data-line-end="11">In this scenario, we want to register an order for a customer by checking a few constraints.<br>
In the first, the <code>orders</code> table includes the following fields:</p>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th style="text-align:left">field</th>
<th style="text-align:left">info</th>
</tr>
</thead>
<tbody>
<tr>
<td style="text-align:left">id</td>
<td style="text-align:left">primary</td>
</tr>
<tr>
<td style="text-align:left">customer_id</td>
<td style="text-align:left">foreign key</td>
</tr>
<tr>
<td style="text-align:left">amount</td>
<td style="text-align:left">int: between: 10000000, 12000000, 15000000, 20000000</td>
</tr>
<tr>
<td style="text-align:left">invoice_count</td>
<td style="text-align:left">between: 6,9,12</td>
</tr>
<tr>
<td style="text-align:left">status</td>
<td style="text-align:left">CHECK_HAVING_ACCOUNT,OPENING_BANK_ACCOUNT</td>
</tr>
</tbody>
</table>
<p class="has-line-data" data-line-start="20" data-line-end="21">And <code>customers</code> is:</p>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th style="text-align:left">field</th>
<th style="text-align:left">info</th>
</tr>
</thead>
<tbody>
<tr>
<td style="text-align:left">id</td>
<td style="text-align:left">primary</td>
</tr>
<tr>
<td style="text-align:left">bank_account_number</td>
<td style="text-align:left">string</td>
</tr>
<tr>
<td style="text-align:left">status</td>
<td style="text-align:left">normal,blocked</td>
</tr>
<tr>
<td style="text-align:left">complete_info</td>
<td style="text-align:left">true/false</td>
</tr>
<tr>
<td style="text-align:left">mobile</td>
<td style="text-align:left">string</td>
</tr>
<tr>
<td style="text-align:left">name</td>
<td style="text-align:left">string</td>
</tr>
</tbody>
</table>
<h3 class="code-line" data-line-start="31" data-line-end="32"><a id="Constraints_31"></a>Constraints</h3>
<p class="has-line-data" data-line-start="32" data-line-end="33">The order can be registered if all these constraints are passed.</p>
<ul>
<li class="has-line-data" data-line-start="33" data-line-end="34">Customer required fields must be filled.</li>
<li class="has-line-data" data-line-start="34" data-line-end="35">The customer is not be blocked.</li>
<li class="has-line-data" data-line-start="35" data-line-end="36">The amount and invoice count must be valid.</li>
<li class="has-line-data" data-line-start="36" data-line-end="37">if customer has <code>bank_account_number</code> the order status must be set to <code>CHECK_HAVING_ACCOUNT</code>, otherwise it must be set to <code>OPENING_BANK_ACCOUNT</code>.</li>
<li class="has-line-data" data-line-start="37" data-line-end="39">Finally, persist order data in database.</li>
</ul>
<p class="has-line-data" data-line-start="39" data-line-end="40">After registering order, we want to send an SMS notification to customer like this text message:</p>
<pre><code class="has-line-data" data-line-start="42" data-line-end="46">Dear {customer_name},
Your order has been registered successfully.
Thank you.
</code></pre>
<h2 class="code-line" data-line-start="47" data-line-end="48"><a id="Notes_47"></a>Notes</h2>
<ul>
<li class="has-line-data" data-line-start="48" data-line-end="49">Please do not use any extra packages.</li>
<li class="has-line-data" data-line-start="49" data-line-end="50">Use the Laravel framework.</li>
<li class="has-line-data" data-line-start="50" data-line-end="51">Writing test(unit/feature) is an advantage.</li>
<li class="has-line-data" data-line-start="51" data-line-end="52">In tests, mock the thirty-party APIs.</li>
<li class="has-line-data" data-line-start="52" data-line-end="53">You have 24/48H to do it</li>
<li class="has-line-data" data-line-start="53" data-line-end="54">After finishing the task, please upload the code to your GitHub repository and send your repo to us.</li>
<li class="has-line-data" data-line-start="54" data-line-end="56">If you have any questions, please feel free to ask via this email address: ‘<a href="https://s4.lendo.ir/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="e984c78f869c85888d8e889ba9858c878d86c7809b">[email&nbsp;protected]</a>’</li>
</ul>
<p class="has-line-data" data-line-start="56" data-line-end="57">Thank you in advance for your valuable time.</p>
<script data-cfasync="false" src="Application%20Overview.md(1)_files/email-decode.min.js"></script><iframe id="goftino_w" title="goftino_widget" allow="microphone" allowfullscreen="true" allowtransparency="true" scrolling="no" srcdoc="&lt;!DOCTYPE html&gt;&lt;html&gt;&lt;/html&gt;" style="left: 0px; bottom: 55px; right: auto; width: 80px; height: 80px;" class="goftino-wakeup"></iframe></body></html>
