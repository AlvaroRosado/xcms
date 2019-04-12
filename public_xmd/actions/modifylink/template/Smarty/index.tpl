{**
 *  \details &copy; 2019 Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  See the Affero GNU General Public License for more details.
 *  You should have received a copy of the Affero GNU General Public License
 *  version 3 along with Ximdex (see LICENSE file).
 *
 *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
 *
 *  @author Ximdex DevTeam <dev@ximdex.com>
 *  @version $Revision$
 *}

<form method="post" action="{$action_url}" id="link_form">
    {include file="actions/components/title_Description.tpl"}
    <div class="action_content">
        <div class="row tarjeta">
            <div class="small-12 columns title_tarjeta">
                <h2 class="h2_general">{t}Modify link{/t}</h2>
            </div>
            <div class="small-8 columns">
                <div class="input">
                    <label for="name" class="label_title label_general">{t}Name{/t} *</label>
                    <p class="icon icon-positioned link">
                        <input type="text" value="{$name}" name="Name" id="name" 
                                class="input_general_icon cajaxg validable not_empty js_val_alphanumeric" />
                    </p>
                </div>
            </div>
            <div class="small-4 columns">
                <div class="input-select icon">
                    <label for="link_type" class="label_title label_general">{t}Type{/t} *</label>
                    <select name="link_type" id="link_type" class="cajaxg document-type validable not_empty">
                        <option value="url" selected>URL (http:// or https://)</option>
                        <option value="email">E-mail (mailto:)</option>
                    </select>
                </div>
            </div>
            <div class="small-12 columns">
                <div class="input">
                    <label for="url" class="label_title label_general">{t}URL{/t} *</label>
                    <input type="text" name="Url" value="{$url}" id="url" class="input_general cajaxg validable not_empty" />
                </div>
            </div>
            <div class="small-12 columns">
                <div class="input">
                    <label for="description" class="label_title label_general"><span>{t}Description{/t}</span></label>
                    <input type="text" name="Description" value="{$description}" id="description" class="input_general cajaxg" />
                </div>
                <fieldset class="buttons-form ">
                    {button label="Modify" class='validate  btn main_action'}
                </fieldset>
            </div>
        </div>
    </div>
</form>