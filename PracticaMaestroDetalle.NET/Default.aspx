<%@ Page Title="" Language="C#" MasterPageFile="~/MasterPage.master" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<%@ Register Assembly="Telerik.Web.UI" Namespace="Telerik.Web.UI" TagPrefix="telerik" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">

   
    <label>Productos</label>
    <telerik:RadGrid ID="RadGrid1" runat="server" AllowPaging="True" PageSize="5" 
        DataSourceID="SqlDataSource1" AllowAutomaticInserts="True" AllowAutomaticUpdates="True"
        AllowAutomaticDeletes="True" GroupPanelPosition="Top">
        <MasterTableView EditMode="InPlace" CommandItemDisplay="Bottom" DataSourceID="SqlDataSource1"
            DataKeyNames="Producto" AutoGenerateColumns="False">
            <Columns>
                <telerik:GridEditCommandColumn />
                <telerik:GridButtonColumn CommandName="Delete" Text="Delete" UniqueName="DeleteColumn" />
                <telerik:GridBoundColumn DataField="Producto" HeaderText="Producto" SortExpression="Producto" UniqueName="Producto" FilterControlAltText="Filter Producto column"></telerik:GridBoundColumn>
                <telerik:GridBoundColumn DataField="Nombre" HeaderText="Nombre" SortExpression="Nombre" UniqueName="Nombre" FilterControlAltText="Filter Nombre column"></telerik:GridBoundColumn>
                <telerik:GridBoundColumn DataField="Descripcion" HeaderText="Descripcion" SortExpression="Descripcion" UniqueName="Descripcion" FilterControlAltText="Filter Descripcion column"></telerik:GridBoundColumn>
                <telerik:GridBoundColumn DataField="FechaIngreso" HeaderText="FechaIngreso" SortExpression="FechaIngreso" UniqueName="FechaIngreso" DataType="System.DateTime" FilterControlAltText="Filter FechaIngreso column"></telerik:GridBoundColumn>
            </Columns>

            <EditFormSettings>
      <EditColumn UniqueName="EditCommandColumn1" />
      <PopUpSettings ScrollBars="None" />
    </EditFormSettings>
    
  </MasterTableView>
</telerik:RadGrid>
    <asp:SqlDataSource ID="SqlDataSource1" runat="server" ConnectionString="<%$ ConnectionStrings:CN %>"
        SelectCommand="SELECT [Producto] ,[Nombre],[Descripcion],[FechaIngreso]FROM [Productos]"
        DeleteCommand="DELETE FROM [Productos] WHERE [Producto]=@Producto"
        InsertCommand="INSERT INTO [Productos] ([Producto] ,[Nombre],[Descripcion],[FechaIngreso]) VALUES (@Producto,@Nombre,@Descripcion,@FechaIngreso)"
        UpdateCommand="UPDATE [Productos] SET [Nombre]=@Nombre,[Descripcion]=@Descripcion,[FechaIngreso]=@FechaIngreso WHERE [Producto] =@Producto">
        <DeleteParameters>
            <asp:Parameter Name="Producto" Type="String" />
        </DeleteParameters>
        <InsertParameters>
            <asp:Parameter Name="Producto" Type="String" />
            <asp:Parameter Name="Nombre" Type="String" />
            <asp:Parameter Name="Descripcion" Type="String" />
            <asp:Parameter Name="FechaIngreso" Type="DateTime" />
        </InsertParameters>
        <UpdateParameters>
            <asp:Parameter Name="Nombre" Type="String" />
            <asp:Parameter Name="Descripcion" Type="String" />
            <asp:Parameter Name="FechaIngreso" Type="DateTime" />
            <asp:Parameter Name="Producto" Type="String" />
    </UpdateParameters>
 </asp:SqlDataSource>

    <br /><br /><br /><br />

    <label>Movimientos</label>
    <telerik:RadGrid ID="RadGrid2" runat="server" AllowPaging="True" PageSize="5"
        DataSourceID="SqlDataSource2" AllowAutomaticInserts="True" AllowAutomaticUpdates="True"
        AllowAutomaticDeletes="True" GroupPanelPosition="Top" CellSpacing="-1" GridLines="Both">
        <MasterTableView EditMode="InPlace" CommandItemDisplay="Bottom" DataSourceID="SqlDataSource2"
            DataKeyNames="Movimiento" AutoGenerateColumns="False">
            <Columns>
                <telerik:GridEditCommandColumn />
                <telerik:GridButtonColumn CommandName="Delete" Text="Delete" UniqueName="DeleteColumn" />
                <telerik:GridBoundColumn DataField="Movimiento" ReadOnly="True" HeaderText="Movimiento" SortExpression="Movimiento" UniqueName="Movimiento" DataType="System.Int32" FilterControlAltText="Filter Movimiento column"></telerik:GridBoundColumn>                
                <telerik:GridTemplateColumn HeaderText="Producto" UniqueName="Producto" DataField="Producto">
                        <ItemTemplate>
                            <%#DataBinder.Eval(Container.DataItem, "Producto")%>
                        </ItemTemplate>
                        <EditItemTemplate>
                            <telerik:RadDropDownList runat="server" ID="ProductoID" DataValueField="Producto"
                                DataTextField="Producto" DataSourceID="SqlDataSource3"  SelectedValue='<%#Bind("Producto") %>'>
                            </telerik:RadDropDownList>
                        </EditItemTemplate>
                </telerik:GridTemplateColumn>
                
                <telerik:GridBoundColumn DataField="TipoMovimiento" HeaderText="TipoMovimiento" SortExpression="TipoMovimiento" UniqueName="TipoMovimiento" DataType="System.Int16" FilterControlAltText="Filter TipoMovimiento column"></telerik:GridBoundColumn>
                <telerik:GridBoundColumn DataField="Fecha" HeaderText="Fecha" SortExpression="Fecha" UniqueName="Fecha" DataType="System.DateTime" FilterControlAltText="Filter Fecha column"></telerik:GridBoundColumn>
                <telerik:GridBoundColumn DataField="Cantidad" HeaderText="Cantidad" SortExpression="Cantidad" UniqueName="Cantidad" DataType="System.Decimal" FilterControlAltText="Filter Cantidad column"></telerik:GridBoundColumn>
            </Columns>

            <EditFormSettings>
      <EditColumn UniqueName="EditCommandColumn1" />
      <PopUpSettings ScrollBars="None" />
    </EditFormSettings>
    
  </MasterTableView>
</telerik:RadGrid>
    <asp:SqlDataSource ID="SqlDataSource2" runat="server" ConnectionString="<%$ ConnectionStrings:CN %>"
        SelectCommand="SELECT [Movimiento],[Producto],[TipoMovimiento],[Fecha],[Cantidad] FROM [Inventario]"
        DeleteCommand="DELETE FROM [Inventario]WHERE [Movimiento]=@Movimiento"
        InsertCommand="INSERT INTO [Inventario] ( [Producto],[TipoMovimiento],[Fecha],[Cantidad]) VALUES (@Producto,@TipoMovimiento,@Fecha,@Cantidad)"
        UpdateCommand="UPDATE Inventario SET [Producto]=@Producto,[TipoMovimiento]=@TipoMovimiento,[Fecha]=@Fecha,[Cantidad]=@Cantidad
WHERE [Movimiento]=@Movimiento">
        <DeleteParameters>
            <asp:Parameter Name="Movimiento" Type="Int16" />
        </DeleteParameters>
        <InsertParameters>
            <asp:Parameter Name="Producto" Type="String" />
            <asp:Parameter Name="TipoMovimiento" Type="Int16" />
            <asp:Parameter Name="Fecha" Type="DateTime" />
            <asp:Parameter Name="Cantidad" Type="Int16" />
        </InsertParameters>
        <UpdateParameters>
            <asp:Parameter Name="Producto" Type="String" />
            <asp:Parameter Name="TipoMovimiento" Type="Int16" />
            <asp:Parameter Name="Fecha" Type="DateTime" />
            <asp:Parameter Name="Cantidad" Type="Int16" />
            <asp:Parameter Name="Movimiento" Type="Int16"></asp:Parameter>
        </UpdateParameters>
 </asp:SqlDataSource>

    <asp:SqlDataSource ID="SqlDataSource3" runat="server" ConnectionString='<%$ ConnectionStrings:CN %>' SelectCommand="SELECT [Producto], [Nombre] FROM [Productos]"></asp:SqlDataSource>
</asp:Content>

