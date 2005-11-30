import org.eclipse.swt.*;
import org.eclipse.swt.widgets.*;

public class OkButton {

public static void main(String[] args) {
	Display display = new Display();
	Shell shell = new Shell(display, SWT.CLOSE);
	shell.setText("Main");
	Menu menu = new Menu(shell, SWT.BAR);
	shell.setMenuBar(menu);
	shell.addListener(SWT.Close, new Listener() {
		public void handleEvent(Event e) {
			System.out.println("Ok button tapped");
		}
	});
	
	shell.open();
	
	while (!shell.isDisposed()) {
		if (!display.readAndDispatch())
			display.sleep();
	}
	display.dispose();
}
}

